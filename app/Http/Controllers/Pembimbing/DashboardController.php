<?php

namespace App\Http\Controllers\Pembimbing;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $divisionName = $user->division->name ?? 'Aplikasi dan Informatika';

        $interns = User::where('id', '!=', $user->id)
            ->whereNotIn('role', [
                'admin', 
                'pembimbing', 
                \App\Enums\UserRole::Admin ?? 'admin', 
                \App\Enums\UserRole::Pembimbing ?? 'pembimbing'
            ])
            ->where(function ($q) use ($divisionName, $user) {
                $q->whereHas('registrations', function ($query) use ($divisionName) {
                    $query->whereIn('status', [
                        \App\Enums\RegistrationStatus::Accepted->value ?? 'accepted', 
                        'accepted', 
                        'diterima',
                        'Accepted'
                    ])->whereHas('position', function ($posQuery) use ($divisionName) {
                        $posQuery->where('nama_posisi', 'LIKE', '%' . $divisionName . '%');
                    });
                })
                ->orWhere(function($query) use ($user) {
                    $query->whereNotNull('division_id')
                          ->where('division_id', $user->division_id);
                });
            })->get();

        $materialCount = Material::where('pembimbing_id', $user->id)->count();
        $taskCount = Task::where('pembimbing_id', $user->id)->count();

        return view('pembimbing.dashboard', compact('interns', 'materialCount', 'taskCount', 'user'));
    }
}
