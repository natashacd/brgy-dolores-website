<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorCounter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorCounterController extends Controller
{
    public function getCount()
    {
        $today = Carbon::now()->toDateString();
        $counter = VisitorCounter::where('date', $today)->first();
        
        return response()->json([
            'count' => $counter ? $counter->count : 0
        ]);
    }

    public function incrementCount()
    {
        $today = Carbon::now()->toDateString();
        
        $counter = VisitorCounter::firstOrCreate(
            ['date' => $today],
            ['count' => 0]
        );
        
        $counter->increment('count');
        
        return response()->json([
            'count' => $counter->count
        ]);
    }
}