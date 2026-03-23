<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit_Logs;

class AuditLogsController extends Controller
{
    public function index()
    {
        $logs = Audit_Logs::orderBy('created_at', 'desc')->get();
        return response()->json($logs);
    }
}
