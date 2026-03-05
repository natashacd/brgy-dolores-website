<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorCounter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorCounterController extends Controller
{
    /**
     * Get simple visitor count (for footer display)
     */
    public function getSimpleCount()
    {
        try {
            // Get total unique visitors based on IP (optional)
            // You can change this to count all visits or unique visitors
            $totalVisitors = VisitorCounter::count(); // Count all visits
            // $totalVisitors = VisitorCounter::distinct('ip_address')->count('ip_address'); // Count unique IPs

            return response()->json([
                'status' => 'success',
                'data' => [
                    'count' => $totalVisitors
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch visitor count'
            ], 500);
        }
    }

    /**
     * Track a visitor (call this on every page load)
     */
    public function trackVisitor(Request $request)
    {
        try {
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $pageUrl = $request->input('page_url', '/');
            $now = Carbon::now();

            // Optional: Check if this IP has visited recently (e.g., within last 30 minutes)
            $recentVisit = VisitorCounter::where('ip_address', $ip)
                ->where('visited_at', '>=', Carbon::now()->subMinutes(30))
                ->first();

            // Only record if no recent visit from this IP (prevents spam from page refreshes)
            if (!$recentVisit) {
                // Record this visit
                VisitorCounter::create([
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'page_url' => $pageUrl,
                    'visited_at' => $now
                ]);
            }

            // Get updated count
            $totalVisitors = VisitorCounter::count();

            return response()->json([
                'status' => 'success',
                'message' => 'Visitor tracked successfully',
                'data' => [
                    'count' => $totalVisitors
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to track visitor'
            ], 500);
        }
    }
}