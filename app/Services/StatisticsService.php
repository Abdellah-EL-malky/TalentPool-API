<?php
namespace App\Services;

use App\Models\Announcement;
use App\Models\Application;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function getRecruiterStatistics($userId)
    {
        // Statistiques pour un recruteur spécifique
        $announcements = Announcement::where('user_id', $userId)->get();

        return [
            'total_announcements' => $announcements->count(),
            'active_announcements' => $announcements->where('status', 'active')->count(),
            'applications_per_announcement' => $announcements->map(function ($announcement) {
                return [
                    'announcement_id' => $announcement->id,
                    'title' => $announcement->title,
                    'total_applications' => $announcement->applications->count(),
                    'application_status_breakdown' => $announcement->applications
                        ->groupBy('status')
                        ->map->count()
                ];
            }),
            'recent_applications' => Application::whereIn('announcement_id', $announcements->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
        ];
    }

    public function getAdminStatistics()
    {
        // Statistiques globales pour l'administrateur
        return [
            'total_users' => \App\Models\User::count(),
            'total_announcements' => Announcement::count(),
            'total_applications' => Application::count(),
            'application_status_distribution' => Application::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'monthly_application_trend' => Application::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_applications')
            )
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'recruiter_activity' => \App\Models\User::where('role', 'recruiter')
                ->withCount('announcements')
                ->orderBy('announcements_count', 'desc')
                ->take(10)
                ->get()
        ];
    }
}
