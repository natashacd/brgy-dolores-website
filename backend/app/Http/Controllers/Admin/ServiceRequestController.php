<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident_Service_Request;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident_Service_Request::with([
            'resident.information',
            'resident.address',
        ])->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->get()->map(function ($req) {
            $info    = $req->resident?->information;
            $address = $req->resident?->address;

            return [
                'id'                    => $req->id,
                'type'                  => $req->type,
                'image_path'            => $req->image_path,
                'preferred_date'        => $req->preferred_date,
                'notes'                 => $req->notes,
                'status'                => $req->status,
                'remarks'               => $req->remarks,
                'created_at'            => $req->created_at,
                'updated_at'            => $req->updated_at,

                'resident_id'           => $req->resident_id,
                'resident_name'         => $info
                    ? trim(($info->first_name ?? '') . ' ' . ($info->middle_name ? $info->middle_name . ' ' : '') . ($info->last_name ?? ''))
                    : '—',
                'resident_email'        => $req->resident?->email,
                'resident_image'        => $info?->image_path,
                'resident_contact'      => $info?->contact_number,
                'resident_sex'          => $info?->sex,
                'resident_birth_date'   => $info?->birth_date,
                'resident_birth_place'  => $info?->birth_place,
                'resident_civil_status' => $info?->civil_status,
                'resident_nationality'  => $info?->nationality,
                'resident_occupation'   => $info?->occupation,
                'resident_house_no'     => $address?->house_no,
                'resident_purok'        => $address?->purok,
                'resident_sitio'        => $address?->sitio,
            ];
        });

        return response()->json($requests);
    }

    public function approve($id)
    {
        $request = Resident_Service_Request::findOrFail($id);
        $request->update(['status' => 'approved']);

        return response()->json(['message' => 'Request approved successfully.']);
    }

    public function reject($id, Request $request)
    {
        $serviceRequest = Resident_Service_Request::findOrFail($id);
        $serviceRequest->update([
            'status'  => 'disapproved',
            'remarks' => $request->remarks ?? null,
        ]);

        return response()->json(['message' => 'Request disapproved successfully.']);
    }

    public function release($id)
    {
        $serviceRequest = Resident_Service_Request::findOrFail($id);
        $serviceRequest->update([
            'status'  => 'released',
        ]);

        return response()->json(['message' => 'Request released successfully.']);
    }

}
