<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();
        $result = $this->authService->register($validatedData);

        return response()->json($result, 201);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials);

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json(['token' => $result['token']]);
    }

    public function logout()
    {
        $result = $this->authService->logout();

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 500);
        }

        return response()->json(['message' => $result['message']]);
    }

}
