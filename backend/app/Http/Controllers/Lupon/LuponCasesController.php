<?php

namespace App\Http\Controllers\Lupon;

use App\Http\Controllers\Controller;
use App\Models\Lupon_Cases;
use App\Models\Schedule_Summon;
use Illuminate\Http\Request;

class LuponCasesController extends Controller
{
    /**
     * List all lupon cases (for lupon members).
     */
    public function index()
    {
        $cases = Lupon_Cases::latest()->get();
        return response()->json($cases);
    }

    /**
     * List only approved cases.
     */
    public function approvedCases()
    {
        $cases = Lupon_Cases::where('status', 'approved')
            ->latest()
            ->get();

        return response()->json($cases);
    }

    /**
     * View a single case.
     */
    public function show($id)
    {
        $case = Lupon_Cases::findOrFail($id);
        return response()->json($case);
    }

    /**
     * Approve a case — sets status to 'approved'.
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
            'case'    => $case->fresh(),
        ]);
    }

    /**
     * Disapprove a case — requires remarks, sets status to 'disapproved'.
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
            'case'    => $case->fresh(),
        ]);
    }

    /**
     * Close a case — sets status to 'closed'.
     */
    public function close($id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if ($case->status !== 'approved') {
            return response()->json([
                'message' => 'Only approved cases can be closed.',
            ], 422);
        }

        $case->update(['status' => 'closed']);

        return response()->json([
            'message' => 'Case closed successfully.',
            'case'    => $case->fresh(),
        ]);
    }

    /**
     * Schedule a summon — sets status to 'scheduled' and creates a schedule_summon row.
     */
    public function summon(Request $request, $id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if ($case->status !== 'approved') {
            return response()->json([
                'message' => 'Only approved cases can be scheduled for summon.',
            ], 422);
        }

        $request->validate([
            'date'  => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $case->update(['status' => 'scheduled']);

        $summon = Schedule_Summon::create([
            'case_id' => $case->id,
            'date'    => $request->date,
            'notes'   => $request->notes,
        ]);

        return response()->json([
            'message' => 'Summon scheduled successfully.',
            'case'    => $case->fresh(),
            'summon'  => $summon,
        ]);
    }
}