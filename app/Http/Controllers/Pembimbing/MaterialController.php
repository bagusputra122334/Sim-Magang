<?php

namespace App\Http\Controllers\Pembimbing;

use App\Enums\SubmissionType;
use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        if (!in_array($perPage, [10, 25, 50])) {
            $perPage = 10;
        }

        $materials = Material::where('pembimbing_id', Auth::id())
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('year'), function ($q) use ($request) {
                $q->whereYear('created_at', $request->input('year'));
            })
            ->when($request->filled('month'), function ($q) use ($request) {
                $q->whereMonth('created_at', $request->input('month'));
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // Ambil tahun unik yang ada pada data materi milik pembimbing yang sedang login
        $dbYears = Material::where('pembimbing_id', Auth::id())
            ->whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn($dt) => (int) \Illuminate\Support\Carbon::parse($dt)->format('Y'))
            ->unique()
            ->values();

        $currentYear = (int) date('Y');
        // Gabungkan tahun dari database dengan rentang tahun saat ini hingga tahun depan
        $availableYears = $dbYears->merge([$currentYear, $currentYear + 1])
            ->unique()
            ->sortDesc()
            ->values();

        return view('pembimbing.materials.index', compact('materials', 'availableYears'));
    }

    public function create()
    {
        $submissionTypes = SubmissionType::cases();
        return view('pembimbing.materials.create', compact('submissionTypes'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_task' => $request->has('is_task') ? 1 : 0,
        ]);
        $request->merge(['pembimbing_id' => Auth::id()]);

        $isTask = $request->has('is_task');

        $validated = $request->validate([
            'pembimbing_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
            'youtube_url' => 'nullable|url|max:255',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|date',
            'submission_type' => [
                'nullable',
                Rule::when($isTask, [Rule::enum(SubmissionType::class)]),
            ],
        ]);

        $materialData = $validated;
        $materialData['is_task'] = $isTask ? 1 : 0;
        $materialData['deadline'] = $request->input('deadline');
        $materialData['submission_type'] = $isTask
            ? $request->input('submission_type') ?: null
            : null;

        if ($request->hasFile('file_materi')) {
            $materialData['file_materi'] = $request->file('file_materi')->store('materials/documents', 'public');
        }

        Material::create($materialData);

        return redirect()->route('pembimbing.materials.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }
        $submissionTypes = SubmissionType::cases();
        return view('pembimbing.materials.edit', compact('material', 'submissionTypes'));
    }

    public function update(Request $request, Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $isTask = $request->has('is_task');

        $request->merge([
            'is_task' => $isTask ? 1 : 0,
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
            'youtube_url' => 'nullable|url|max:255',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|date',
            'submission_type' => [
                'nullable',
                Rule::when($isTask, [Rule::enum(SubmissionType::class)]),
            ],
        ]);

        $materialData = $validated;
        $materialData['is_task'] = $isTask ? 1 : 0;
        $materialData['deadline'] = $request->input('deadline');
        $materialData['submission_type'] = $isTask
            ? $request->input('submission_type') ?: null
            : null;

        if ($request->hasFile('file_materi')) {
            if (!empty($material->file_materi)) {
                Storage::disk('public')->delete($material->file_materi);
            }
            $materialData['file_materi'] = $request->file('file_materi')->store('materials/documents', 'public');
        }

        if ($request->boolean('remove_file_materi') && !empty($material->file_materi)) {
            Storage::disk('public')->delete($material->file_materi);
            $materialData['file_materi'] = null;
        }

        $material->update($materialData);

        return redirect()->route('pembimbing.materials.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        if (!empty($material->file_materi)) {
            Storage::disk('public')->delete($material->file_materi);
        }

        $material->delete();

        return redirect()->route('pembimbing.materials.index')->with('success', 'Materi berhasil dihapus.');
    }

    public function submissions(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        return view('pembimbing.materials.submissions', compact('material'));
    }
}
