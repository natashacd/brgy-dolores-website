<?php

namespace App\Http\Controllers\Lupon;

use App\Http\Controllers\Controller;
use App\Models\Lupon_Cases;
use Illuminate\Http\Request;

class LuponCasesController extends Controller
{
    /**
     * List all lupon cases (for lupon members / admin).
     */
    public function index()
    {
        $cases = Lupon_Cases::latest()->get();
        return response()->json($cases);
    }

    /**
     * View a single case.
     */
    public function show($id)
    {
        $case = Lupon_Cases::with('user:id,name')->findOrFail($id);

        return response()->json($case);
    }

    /**
     * Approve a case — sets status to 'mediation' (case is being acted on).
     */
    public function approve(Request $request, $id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if ($case->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending cases can be approved.',
            ], 422);
        }

        $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $case->update([
            'status'  => 'approved',
            'remarks' => $request->remarks ?? $case->remarks,
        ]);

        return response()->json([
            'message' => 'Case approved successfully.',
            'case'    => $case->fresh('user'),
        ]);
    }

    /**
     * Disapprove / reject a case — requires remarks.
     */
    public function disapprove(Request $request, $id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if ($case->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending cases can be disapproved.',
            ], 422);
        }

        $request->validate([
            'remarks' => 'required|string',
        ]);

        $case->update([
            'status'  => 'disapproved',
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'message' => 'Case disapproved.',
            'case'    => $case->fresh('user'),
        ]);
    }

}