<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Lupon_Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LuponCasesController extends Controller
{
    public function index()
    {
        $cases = Lupon_Cases::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($cases);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'          => 'required|string|in:incident,dispute,report,other',
            'title'         => 'required|string|max:255',
            'incident_date' => 'required|date|before_or_equal:today',
            'location'      => 'required|string|max:255',
            'description'   => 'required|string',
        ]);

        $case = Lupon_Cases::create([
            'user_id'       => Auth::id(),
            'type'          => $request->type,
            'title'         => $request->title,
            'incident_date' => Carbon::parse($request->incident_date)->format('Y-m-d'),
            'location'      => $request->location,
            'description'   => $request->description,
            'status'        => 'pending',
        ]);

        return response()->json([
            'message' => 'Complaint filed successfully.',
            'case'    => $case,
        ], 201);
    }

    public function viewCase($id)
    {
        $case = Lupon_Cases::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json($case);
    }

    public function destroy($id)
    {
        $case = Lupon_Cases::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($case->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending cases can be deleted.',
            ], 422);
        }

        $case->delete();

        return response()->json([
            'message' => 'Case deleted successfully.',
        ]);
    }
}