<?php

namespace App\Http\Controllers\Lupon;

use App\Http\Controllers\Controller;
use App\Models\Lupon_Cases;
use App\Models\Schedule_Summon;
use Illuminate\Http\Request;

class LuponCasesController extends Controller
{

    public function index()
    {
        $cases = Lupon_Cases::with(['summon', 'user.information'])->latest()->get();
        return response()->json($cases);
    }

    public function approvedCases()
    {
        $cases = Lupon_Cases::with(['summon', 'user.information'])
            ->whereIn('status', ['approved', 'scheduled'])
            ->latest()
            ->get();

        return response()->json($cases);
    }

    public function disapprovedCases()
    {
        $cases = Lupon_Cases::with('user.information')
            ->where('status', 'disapproved')
            ->latest()
            ->get();

        return response()->json($cases);
    }

    public function closedCases()
    {
        $cases = Lupon_Cases::with(['summon', 'user.information'])
            ->where('status', 'closed')
            ->latest()
            ->get();

        return response()->json($cases);
    }

    public function show($id)
    {
        $case = Lupon_Cases::with(['summon', 'user.information'])->findOrFail($id);
        return response()->json($case);
    }

    public function approve(Request $request, $id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if ($case->status !== 'pending') {
            return response()->json(['message' => 'Only pending cases can be approved.'], 422);
        }

        $case->update([
            'status'  => 'approved',
            'remarks' => $request->remarks ?? $case->remarks,
        ]);

        return response()->json([
            'message' => 'Case approved successfully.',
            'case'    => $case->fresh()->load('summon'),
        ]);
    }


    public function disapprove(Request $request, $id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if ($case->status !== 'pending') {
            return response()->json(['message' => 'Only pending cases can be disapproved.'], 422);
        }

        $request->validate(['remarks' => 'required|string']);

        $case->update([
            'status'  => 'disapproved',
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'message' => 'Case disapproved.',
            'case'    => $case->fresh(),
        ]);
    }

    public function close($id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if (!in_array($case->status, ['approved', 'scheduled'])) {
            return response()->json(['message' => 'Only approved or scheduled cases can be closed.'], 422);
        }

        $case->update(['status' => 'closed']);

        return response()->json([
            'message' => 'Case closed successfully.',
            'case'    => $case->fresh(),
        ]);
    }

    public function summon(Request $request, $id)
    {
        $case = Lupon_Cases::findOrFail($id);

        if (!in_array($case->status, ['approved', 'scheduled'])) {
            return response()->json(['message' => 'Only approved cases can be scheduled for summon.'], 422);
        }

        $request->validate([
            'date'  => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $case->update(['status' => 'scheduled']);

        $summon = Schedule_Summon::updateOrCreate(
            ['case_id' => $case->id],
            [
                'date'  => $request->date,
                'notes' => $request->notes,
            ]
        );

        return response()->json([
            'message' => 'Summon scheduled successfully.',
            'case'    => $case->fresh()->load('summon'),
            'summon'  => $summon,
        ]);
    }
}