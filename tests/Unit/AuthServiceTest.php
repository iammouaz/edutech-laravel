<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mockery;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\User;

class AuthServiceTest extends TestCase
{
    protected $authService;
    protected $userRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepositoryMock = Mockery::mock(UserRepository::class);

        $this->authService = new AuthService($this->userRepositoryMock);
    }

    /** @test */
    public function it_can_register_a_user_successfully()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'teacher',
        ];

        Hash::shouldReceive('make')
            ->once()
            ->with($userData['password'])
            ->andReturn('hashed_password');

        $mockUser = Mockery::mock(User::class . ', ' . JWTSubject::class)->makePartial();
        $mockUser->shouldReceive('getJWTIdentifier')->andReturn(1);
        $mockUser->shouldReceive('getJWTCustomClaims')->andReturn([]);

        $mockUser->shouldReceive('getAttribute')->with('name')->andReturn($userData['name']);
        $mockUser->shouldReceive('getAttribute')->with('email')->andReturn($userData['email']);
        $mockUser->shouldReceive('getAttribute')->with('role')->andReturn($userData['role']);

        $this->userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) use ($userData) {
                return $data['email'] === $userData['email']
                    && $data['password'] === 'hashed_password'
                    && $data['role'] === $userData['role'];
            }))
            ->andReturn($mockUser);

        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($mockUser)
            ->andReturn('jwt_token');

        $result = $this->authService->register($userData);

        $this->assertEquals('jwt_token', $result['token']);
        $this->assertEquals('john@example.com', $result['user']['email']);
        $this->assertEquals('teacher', $result['user']['role']);
    }

    /** @test */
    public function it_fails_login_with_invalid_credentials()
    {
        $credentials = ['email' => 'john@example.com', 'password' => 'wrong_password'];

        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $result = $this->authService->login($credentials);

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid Credentials', $result['error']);
        $this->assertNull($result['token']);
    }

    /** @test */
    public function it_can_login_successfully_with_valid_credentials()
    {
        $credentials = ['email' => 'john@example.com', 'password' => 'correct_password'];

        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn('jwt_token');

        $result = $this->authService->login($credentials);

        $this->assertTrue($result['success']);
        $this->assertEquals('jwt_token', $result['token']);
    }

    /** @test */
    public function it_fails_to_login_due_to_token_creation_error()
    {
        $credentials = ['email' => 'john@example.com', 'password' => 'correct_password'];

        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andThrow(new JWTException());

        $result = $this->authService->login($credentials);

        $this->assertFalse($result['success']);
        $this->assertEquals('Could not create token', $result['error']);
        $this->assertNull($result['token']);
    }

    /** @test */
    public function it_can_logout_successfully()
    {
        JWTAuth::shouldReceive('getToken')
            ->once()
            ->andReturn('token');

        JWTAuth::shouldReceive('invalidate')
            ->once()
            ->with('token')
            ->andReturn(true);

        $result = $this->authService->logout();

        $this->assertTrue($result['success']);
        $this->assertEquals('Successfully logged out', $result['message']);
    }

    /** @test */
    public function it_fails_to_logout_due_to_token_error()
    {
        JWTAuth::shouldReceive('getToken')
            ->once()
            ->andReturn('token');

        JWTAuth::shouldReceive('invalidate')
            ->once()
            ->with('token')
            ->andThrow(new JWTException());

        $result = $this->authService->logout();

        $this->assertFalse($result['success']);
        $this->assertEquals('Failed to log out, please try again', $result['error']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
