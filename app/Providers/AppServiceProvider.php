<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\Interfaces\SubmissionRepositoryInterface;
use App\Repositories\SubmissionRepository;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use App\Repositories\AssignmentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);

        $this->app->bind(SubmissionRepositoryInterface::class, SubmissionRepository::class);

        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
