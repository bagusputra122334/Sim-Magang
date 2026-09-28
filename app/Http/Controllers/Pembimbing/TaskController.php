<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('pembimbing_id', Auth::id())->latest()->paginate(10);
        return view('pembimbing.tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('pembimbing.tasks.create');
    }

    public function store(Request $request)
    {
        $request->merge(['pembimbing_id' => Auth::id()]);
        
        $validated = $request->validate([
            'pembimbing_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'allowed_format' => 'required|array',
            'allowed_format.*' => 'string|in:pdf,zip,link',
            'deadline' => 'required|date',
        ]);

        Task::create($validated);

        return redirect()->route('pembimbing.tasks.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Task $task)
    {
        if ($task->pembimbing_id !== Auth::id()) {
            abort(403);
        }
        return view('pembimbing.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'allowed_format' => 'required|array',
            'allowed_format.*' => 'string|in:pdf,zip,link',
            'deadline' => 'required|date',
        ]);

        $task->update($validated);

        return redirect()->route('pembimbing.tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        if ($task->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('pembimbing.tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
