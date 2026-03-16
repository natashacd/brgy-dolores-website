<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Resident_Service_Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceRequestController extends Controller
{
    private array $serviceTypeMap = [
        'BRGY ID Request'                      => 'Brgy ID',
        'BRGY Business Clearance / Permit'     => 'Brgy Business Clearance',
        'BRGY Certificate of Indigency'        => 'Brgy Certificate of Indigency',
        'BRGY Certificate of Residency'        => 'Brgy Certificate of Residency',
        'Certifications for Specific Purposes' => 'Brgy Certification',
    ];

    public function index()
    {
        $requests = Resident_Service_Request::where('resident_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_type'   => 'required|string',
            'preferred_date' => 'required|date|after_or_equal:today',
            'document'       => 'required|file|mimes:jpeg,jpg,png,gif,pdf|max:4096',
            'notes'          => 'nullable|string',
        ]);

        $user      = Auth::user();
        $file      = $request->file('document');
        $extension = $file->getClientOriginalExtension();
        $fileName  = $user->id . '.' . $extension;
        $imagePath = $file->storeAs('request_images', $fileName, 'public');

        $preferredDate = Carbon::parse($request->preferred_date)->format('F j, Y');
        $mappedType    = $this->serviceTypeMap[$request->service_type] ?? $request->service_type;

        $serviceRequest = Resident_Service_Request::create([
            'resident_id'    => $user->id,
            'type'           => $mappedType,
            'image_path'     => $imagePath,
            'preferred_date' => $preferredDate,
            'notes'          => $request->notes ?: 'N/A',
            'status'         => 'pending',
        ]);

        return response()->json([
            'message' => 'Service request submitted successfully.',
            'request' => $serviceRequest,
        ], 201);
    }
}