<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialProgress;
use App\Models\Submission;
use App\Models\Task;
use App\Models\User;
use App\Enums\UserRole;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LmsController extends Controller
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function dashboard()
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);
        
        if (!$intern->division_id) {
            return redirect()->route('participant.dashboard')->with('error', 'Anda belum ditugaskan ke divisi manapun.');
        }

        $acceptedRegistration = $intern->registrations()
            ->where('status', \App\Enums\RegistrationStatus::Accepted->value ?? \App\Enums\RegistrationStatus::Accepted)
            ->latest()
            ->first();
        $periodeMulai = $acceptedRegistration && $acceptedRegistration->periode_mulai 
            ? \Carbon\Carbon::parse($acceptedRegistration->periode_mulai)->startOfDay() 
            : null;

        $pembimbingIds = User::where('role', UserRole::Pembimbing)
            ->where('division_id', $intern->division_id)
            ->pluck('id');

        $materials = Material::whereIn('pembimbing_id', $pembimbingIds)
            ->when($periodeMulai, function($query, $periodeMulai) {
                return $query->where('created_at', '>=', $periodeMulai);
            })
            ->whereDoesntHave('progresses', function ($query) use ($intern) {
                $query->where('intern_id', $intern->id)->where('is_completed', true);
            })
            ->latest()
            ->get();

        $tasks = Task::whereIn('pembimbing_id', $pembimbingIds)
            ->when($periodeMulai, function($query, $periodeMulai) {
                return $query->where('created_at', '>=', $periodeMulai);
            })
            ->orderBy('deadline', 'asc')
            ->get();

        return view('intern.lms.dashboard', compact('materials', 'tasks'));
    }

    public function showMaterial($id)
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);
        $material = Material::findOrFail($id);

        $pembimbing = User::find($material->pembimbing_id);
        if ($intern->division_id !== null && $pembimbing->division_id !== $intern->division_id) {
            abort(403);
        }

        $progress = MaterialProgress::firstOrCreate(
            ['intern_id' => $intern->id, 'material_id' => $material->id],
            ['is_completed' => false]
        );

        $videoId = null;
        if ($material->youtube_url) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $material->youtube_url, $match);
            if (isset($match[1])) {
                $videoId = $match[1];
            }
        }

        return view('intern.lms.material_show', compact('material', 'progress', 'videoId'));
    }

    public function completeMaterial(Request $request, $id)
    {
        $intern = Auth::user();
        $progress = MaterialProgress::where('intern_id', $intern->id)->where('material_id', $id)->firstOrFail();
        
        if (!$progress->is_completed) {
            $progress->update([
                'is_completed' => true,
                'completed_at' => now()
            ]);
        }
        
        return response()->json(['success' => true]);
    }

    public function showTask($id)
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);
        $task = Task::findOrFail($id);

        $pembimbing = User::find($task->pembimbing_id);
        if ($intern->division_id !== null && $pembimbing->division_id !== $intern->division_id) {
            abort(403);
        }

        $submission = Submission::where('intern_id', $intern->id)->where('task_id', $task->id)->first();

        return view('intern.lms.task_show', compact('task', 'submission'));
    }

    public function submitTask(Request $request, $id)
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);
        $task = Task::findOrFail($id);

        $pembimbing = User::find($task->pembimbing_id);
        if ($intern->division_id !== null && $pembimbing->division_id !== $intern->division_id) {
            abort(403);
        }

        $request->validate([
            'submission_file' => 'nullable|file|mimes:pdf,zip',
            'submission_link' => 'nullable|url',
        ]);

        $submission = Submission::firstOrNew([
            'intern_id' => $intern->id,
            'task_id' => $task->id
        ]);

        if ($request->hasFile('submission_file')) {
            $path = $request->file('submission_file')->store('submissions', 'public');
            $submission->file_path = $path;
        }

        if ($request->filled('submission_link')) {
            $submission->submission_link = $request->submission_link;
        }

        $submission->status = 'submitted';
        $submission->save();

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
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
