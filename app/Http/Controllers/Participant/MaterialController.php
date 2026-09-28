<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\ModuleSubmission;
use App\Models\User;
use App\Enums\UserRole;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function index()
    {
        $user = auth()->user();
        $user = $this->ensureDivisionIdResolved($user);

        $query = Material::query();

        if ($user->division_id) {
            $materialsDivision = (clone $query)
                ->where(function ($q) use ($user) {
                    if (\Illuminate\Support\Facades\Schema::hasColumn('materials', 'division_id')) {
                        $q->where('division_id', $user->division_id)
                          ->orWhereNull('division_id');
                    }

                    $q->orWhereHas('pembimbing', function ($sub) use ($user) {
                        $sub->where('division_id', $user->division_id);
                    });
                })
                ->latest()
                ->get();

            if ($materialsDivision->count() > 0) {
                $page = request()->input('page', 1);
                $perPage = 9;
                $offset = ($page - 1) * $perPage;
                $materials = new \Illuminate\Pagination\LengthAwarePaginator(
                    $materialsDivision->slice($offset, $perPage)->values(),
                    $materialsDivision->count(),
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            } else {
                $materials = Material::latest()->paginate(9);
            }
        } else {
            $materials = Material::latest()->paginate(9);
        }

        return view('participant.materials.index', compact('materials'));
    }

    public function show($id)
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);
        $material = Material::findOrFail($id);

        $pembimbing = User::find($material->pembimbing_id);
        if ($intern->division_id !== null && $pembimbing->division_id !== $intern->division_id) {
            abort(403);
        }

        $submission = ModuleSubmission::where('material_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        $videoId = null;
        if ($material->youtube_url) {
            preg_match(
                '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
                $material->youtube_url,
                $match
            );
            if (isset($match[1])) {
                $videoId = $match[1];
            }
        }

        return view('participant.materials.show', compact('material', 'submission', 'videoId'));
    }

    public function submitTask(Request $request, $id)
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);
        $material = Material::findOrFail($id);

        $pembimbing = User::find($material->pembimbing_id);
        if ($intern->division_id !== null && $pembimbing->division_id !== $intern->division_id) {
            abort(403);
        }

        if (!$material->is_task) {
            return back()->with('error', 'Materi ini bukan merupakan tugas yang perlu dikumpulkan.');
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip,rar,jpg,png,jpeg|max:10240',
            'submission_text' => 'nullable|string',
        ]);

        $path = $request->file('file')->store('submissions', 'public');

        ModuleSubmission::updateOrCreate(
            ['material_id' => $id, 'user_id' => auth()->id()],
            [
                'file_path' => $path,
                'submission_text' => $request->input('submission_text'),
                'status' => 'submitted',
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    protected function ensureDivisionIdResolved(User $intern): User
    {
        if (!empty($intern->division_id)) {
            return $intern;
        }

        $acceptedRegistration = $intern->registrations()
            ->where('status', \App\Enums\RegistrationStatus::Accepted)
            ->where('is_terminated', false)
            ->latest()
            ->first();

        if ($acceptedRegistration === null) {
            $acceptedRegistration = $intern->registrations()
                ->where('status', \App\Enums\RegistrationStatus::Accepted)
                ->latest()
                ->first();
        }

        if ($acceptedRegistration === null) {
            return $intern;
        }

        $divisionId = $this->registrationService->resolveDivisionIdFromRegistration($acceptedRegistration);
        if ($divisionId === null) {
            return $intern;
        }

        $intern->update([
            'division_id' => $divisionId,
        ]);

        return $intern->fresh();
    }
}
