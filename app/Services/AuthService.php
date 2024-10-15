<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials)
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return [
                    'success' => false,
                    'error' => 'Invalid Credentials',
                    'token' => null,
                ];
            }

            return [
                'success' => true,
                'token' => $token,
            ];
        } catch (JWTException $e) {
            return [
                'success' => false,
                'error' => 'Could not create token',
                'token' => null,
            ];
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ['success' => true, 'message' => 'Successfully logged out'];
        } catch (JWTException $e) {
            return ['success' => false, 'error' => 'Failed to log out, please try again'];
        }
    }
}
