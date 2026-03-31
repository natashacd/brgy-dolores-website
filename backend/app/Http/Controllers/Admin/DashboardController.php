<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resident_Service_Request;
use App\Models\Lupon_Cases;

class DashboardController extends Controller
{
    public function getStats()
    {
        $residents = User::where('role_id', 7)->count();
        
        $officials = User::whereHas('role', function ($query) {
            $query->where('role_name', '!=', 'Resident');
        })->count();
        
        $totalCases = Lupon_Cases::count();
        $pendingCases = Lupon_Cases::where('status', 'pending')->count();
        $approvedCases = Lupon_Cases::where('status', 'approved')->count();
        $scheduledCases = Lupon_Cases::where('status', 'scheduled')->count();
        $disapprovedCases = Lupon_Cases::where('status', 'disapproved')->count();
        $closedCases = Lupon_Cases::where('status', 'closed')->count();
        
        $recentCases = Lupon_Cases::with(['user.information'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        
        $totalRequests = Resident_Service_Request::count();
        $pendingRequests = Resident_Service_Request::where('status', 'pending')->count();
        $approvedRequests = Resident_Service_Request::where('status', 'approved')->count();
        $disapprovedRequests = Resident_Service_Request::whereIn('status', ['disapproved', 'rejected'])->count();
        $releasedRequests = Resident_Service_Request::whereIn('status', ['released', 'completed'])->count();
        
        return response()->json([
            'stats' => [
                'totalResidents' => $residents,
                'totalOfficials' => $officials,
                'totalCases' => $totalCases,
                'pendingCases' => $pendingCases,
                'approvedCases' => $approvedCases,
                'scheduledCases' => $scheduledCases,
                'disapprovedCases' => $disapprovedCases,
                'closedCases' => $closedCases,
                'totalRequests' => $totalRequests,
                'pendingRequests' => $pendingRequests,
                'approvedRequests' => $approvedRequests,
                'disapprovedRequests' => $disapprovedRequests,
                'releasedRequests' => $releasedRequests,
            ],
            'recentCases' => $recentCases,
        ]);
    }
}