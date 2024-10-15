<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get all users
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();

        return new UserCollection($users);
    }

    /**
     * Get a single user by ID
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        return new UserResource($user);
    }

    /**
     * Create a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:student,teacher',
        ]);

        $user = $this->userService->createUser($validated);

        return new UserResource($user);
    }

    /**
     * Update an existing user
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255',
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|in:student,teacher',
        ]);

        $user = $this->userService->updateUser($id, $validated);

        return new UserResource($user);
    }

    /**
     * Delete a user
     */
    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
