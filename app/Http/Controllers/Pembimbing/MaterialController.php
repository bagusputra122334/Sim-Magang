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
    public function index()
    {
        $materials = Material::where('pembimbing_id', Auth::id())->latest()->paginate(10);
        return view('pembimbing.materials.index', compact('materials'));
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
