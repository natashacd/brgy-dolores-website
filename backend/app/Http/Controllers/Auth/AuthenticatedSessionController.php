<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user()->load(['information', 'role', 'status', 'address']);

        return response()->json([
            'message' => 'success',
            'user' => [
                'id'         => $user->id,
                'email'      => $user->email,
                'first_name' => $user->information?->first_name,
                'last_name'  => $user->information?->last_name,
                'role'       => $user->role?->role_name,
                'status'     => $user->status?->status,
                'address'    => $user->address?->house_no . ', ' . $user->address?->purok . ', ' . $user->address?->sitio,
            ],
        ]);
    }

    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
