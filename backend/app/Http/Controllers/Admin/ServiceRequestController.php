<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident_Service_Request;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Resident_Service_Request::with([
                'resident.information',
                'staff.information'
            ]);

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && $request->type != '') {
                $query->where('type', $request->type);
            }

            if ($request->has('search') && $request->search != '') {
                $query->whereHas('resident.information', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%')
                      ->orWhere('last_name', 'like', '%' . $request->search . '%');
                });
            }

            $requests = $query->orderBy('created_at', 'desc')
                ->paginate(5)
                ->through(function ($request) {
                    return [
                        'id' => $request->id,
                        'resident_id' => $request->resident_id,
                        'resident_name' => $request->resident->information
                            ? $request->resident->information->first_name . ' ' . $request->resident->information->last_name
                            : 'N/A',
                        'resident_email' => $request->resident->email ?? 'N/A',
                        'resident_contact' => $request->resident->information->contact_number ?? 'N/A',
                        'staff_id' => $request->staff_id,
                        'staff_name' => ($request->staff && $request->staff->information)
                            ? $request->staff->information->first_name . ' ' . $request->staff->information->last_name
                            : null,
                        'type' => $request->type,
                        'status' => $request->status,
                        'created_at' => $request->created_at,
                        'updated_at' => $request->updated_at,
                    ];
                });

            return response()->json($requests);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function show($id)
    {
        $request = Resident_Service_Request::with([
            'resident.information',
            'staff.information'
        ])->findOrFail($id);

        return response()->json([
            'id' => $request->id,
            'resident_id' => $request->resident_id,
            'resident_name' => $request->resident->information
                ? $request->resident->information->first_name . ' ' . $request->resident->information->last_name
                : 'N/A',
            'resident_email' => $request->resident->email,
            'resident_contact' => $request->resident->information->contact_number ?? 'N/A',
            'staff_id' => $request->staff_id,
            'staff_name' => ($request->staff && $request->staff->information)
                ? $request->staff->information->first_name . ' ' . $request->staff->information->last_name
                : null,
            'type' => $request->type,
            'status' => $request->status,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
        ]);
    }

    public function getStaff()
    {
        $staff = User::with('information')
            ->whereHas('role', function ($query) {
                $query->where('role_name', '!=', 'Resident');
            })
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->information
                        ? $user->information->first_name . ' ' . $user->information->last_name
                        : $user->email,
                    'email' => $user->email,
                    'role' => $user->role->role_name,
                ];
            });

        return response()->json($staff);
    }

    public function reassign(Request $request, $id)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $serviceRequest = Resident_Service_Request::findOrFail($id);

        $newStaff = User::findOrFail($request->staff_id);
        if ($newStaff->role->role_name === 'Resident') { 
            return response()->json(['message' => 'Cannot assign to a Resident.'], 400);
        }

        $serviceRequest->update([
            'staff_id' => $request->staff_id,
            'status' => 'processing',
        ]);

        return response()->json([
            'message' => 'Request reassigned successfully.',
            'request' => $serviceRequest->load(['resident.information', 'staff.information']),
        ]);
    }

    public function approve($id)
    {
        $request = Resident_Service_Request::findOrFail($id);

        if ($request->status === 'completed' || $request->status === 'cancelled') {
            return response()->json(['message' => 'Cannot approve a completed or cancelled request.'], 400);
        }

        $request->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Request approved successfully.',
            'request' => $request->load(['resident.information', 'staff.information']),
        ]);
    }

    public function cancel($id)
    {
        $request = Resident_Service_Request::findOrFail($id);

        if ($request->status === 'completed') {
            return response()->json(['message' => 'Cannot cancel a completed request.'], 400);
        }

        $request->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Request cancelled successfully.',
            'request' => $request->load(['resident.information', 'staff.information']),
        ]);
    }
}
