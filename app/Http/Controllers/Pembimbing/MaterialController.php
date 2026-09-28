<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::where('pembimbing_id', Auth::id())->latest()->paginate(10);
        return view('pembimbing.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('pembimbing.materials.create');
    }

    public function store(Request $request)
    {
        $request->merge(['pembimbing_id' => Auth::id()]);
        
        $validated = $request->validate([
            'pembimbing_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'youtube_url' => 'nullable|url|max:255',
            'is_task' => 'nullable|boolean',
            'task_instruction' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $materialData = $validated;
        $materialData['is_task'] = $request->has('is_task') ? 1 : 0;
        $materialData['task_instruction'] = $request->input('task_instruction');
        $materialData['deadline'] = $request->input('deadline');

        Material::create($materialData);

        return redirect()->route('pembimbing.materials.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }
        return view('pembimbing.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'youtube_url' => 'nullable|url|max:255',
            'is_task' => 'nullable|boolean',
            'task_instruction' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $materialData = $validated;
        $materialData['is_task'] = $request->has('is_task') ? 1 : 0;
        $materialData['task_instruction'] = $request->input('task_instruction');
        $materialData['deadline'] = $request->input('deadline');

        $material->update($materialData);

        return redirect()->route('pembimbing.materials.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $material->delete();

        return redirect()->route('pembimbing.materials.index')->with('success', 'Materi berhasil dihapus.');
    }
}
