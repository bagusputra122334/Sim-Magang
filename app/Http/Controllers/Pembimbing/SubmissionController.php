<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function index(Task $task)
    {
        if ($task->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $submissions = $task->submissions()->with('intern')->latest()->paginate(10);
        return view('pembimbing.tasks.show', compact('task', 'submissions'));
    }

    public function review(Request $request, Submission $submission)
    {
        if ($submission->task->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:reviewed,revision',
            'grade_note' => 'nullable|string',
        ]);

        $submission->update($validated);

        return back()->with('success', 'Review berhasil disimpan.');
    }
}
