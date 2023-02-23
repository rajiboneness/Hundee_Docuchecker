<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Borrower;
use App\Models\BorrowerAgreement;

class ApiController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->guard('api')->user()
        ]);
    }

    public function login() {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard('api')->attempt($credentials)) {
            return response()->json(['status' => 401, 'error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function profile() {
        return response()->json(['status' => 200, 'data' => auth()->guard('api')->user()]);
    }

    public function logout() {
        auth()->guard('api')->logout();
        return response()->json(['status' => 200, 'message' => 'Successfully logged out']);
    }
}
