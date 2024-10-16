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

    /**
     * Register a new user.
     *
     * This endpoint allows users to register for an account.
     *
     * @group Authentication
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: johndoe@example.com
     * @bodyParam password string required The password for the user, with a minimum length of 6 characters. Example: secret123
     * @bodyParam password_confirmation string required Must match the password. Example: secret123
     * @bodyParam role string required The role of the user, either 'student' or 'teacher'. Example: student
     * @response 201 {
     *   "id": 1,
     *   "name": "John Doe",
     *   "email": "johndoe@example.com",
     *   "role": "student"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();
        $result = $this->authService->register($validatedData);

        return response()->json($result, 201);
    }

    /**
     * Log in a user.
     *
     * This endpoint authenticates a user and returns a JWT token.
     *
     * @group Authentication
     * @bodyParam email string required The user's email. Example: johndoe@example.com
     * @bodyParam password string required The user's password. Example: secret123
     * @response 200 {
     *   "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
     * }
     * @response 400 {
     *   "error": "Invalid credentials"
     * }
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials);

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json(['token' => $result['token']]);
    }

    /**
     * Log out the current user.
     *
     * This endpoint logs out the authenticated user and invalidates the token.
     *
     * @group Authentication
     * @response 200 {
     *   "message": "Successfully logged out"
     * }
     * @response 500 {
     *   "error": "Could not log out"
     * }
     */
    public function logout()
    {
        $result = $this->authService->logout();

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 500);
        }

        return response()->json(['message' => $result['message']]);
    }
}
