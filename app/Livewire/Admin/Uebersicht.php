<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Uebersicht extends Component
{
    #[Layout('components.layout.admin-dashboard')]
    #[Title('Admin Dashboard')]
    public function render()
    {
        return view('livewire.admin.uebersicht', [
            // Stats
            'totalUsers' => User::where('is_admin', 0)->count(),
            'totalApplications' => Application::count(),
            'pendingApplications' => Application::whereIn('appl_status', ['pending', 'waiting'])->count(),
            'activeProjects' => Application::where('appl_status', 'approved')->count(),

            // Recent Applications
            'recentApplications' => Application::with('user')
                ->whereHas('user')
                ->latest()
                ->take(5)
                ->get(),

            // Recent Users
            'recentUsers' => User::where('is_admin', 0)
                ->latest()
                ->take(5)
                ->get(),

            // Verification Stats
            'unverifiedUsers' => User::where('is_admin', 0)
                ->whereNull('email_verified_at')
                ->count(),
        ]);
    }
}
