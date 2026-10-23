<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        if (!in_array($perPage, [10, 25, 50])) {
            $perPage = 10;
        }

        $tasks = Task::where('pembimbing_id', Auth::id())
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('year'), function ($q) use ($request) {
                $q->whereYear('deadline', $request->input('year'))
                  ->orWhereYear('created_at', $request->input('year'));
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $dbYears = Task::where('pembimbing_id', Auth::id())
            ->whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn($dt) => (int) \Illuminate\Support\Carbon::parse($dt)->format('Y'))
            ->unique()
            ->values();

        $currentYear = (int) date('Y');
        $availableYears = $dbYears->merge([$currentYear, $currentYear + 1])
            ->unique()
            ->sortDesc()
            ->values();

        return view('pembimbing.tasks.index', compact('tasks', 'availableYears'));
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
