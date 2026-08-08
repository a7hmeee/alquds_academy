<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use App\Features\StudentProgress\Repositories\StudentProgressRepositoryInterface;
use App\Features\StudentProgress\Repositories\EloquentStudentProgressRepository;
use App\Events\SubmissionCreated;
use App\Listeners\Recordings\NotifyTeacherNewSubmission;
use App\Features\StudentProgress\Events\StudentProgressCreated;
use App\Features\StudentProgress\Listeners\NotifyTeacherProgressUpdated;
use App\Models\StudentProgress;
use App\Models\StudentProfile;
use App\Models\StudentSubmission;
use App\Models\Circle;
use App\Models\TeacherProfile;
use App\Models\MemorizationAssignment;
use App\Models\MemorizationSession;
use App\Policies\StudentProgressPolicy;
use App\Policies\StudentProfilePolicy;
use App\Policies\StudentSubmissionPolicy;
use App\Policies\CirclePolicy;
use App\Policies\TeacherProfilePolicy;
use App\Policies\MemorizationAssignmentPolicy;
use App\Policies\MemorizationSessionPolicy;
use App\Events\MemorizationAssignmentCreated;
use App\Listeners\Memorization\NotifyStudentNewAssignment;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            StudentProgressRepositoryInterface::class,
            EloquentStudentProgressRepository::class
        );
    }

    public function boot(): void
    {
        Event::listen(SubmissionCreated::class, NotifyTeacherNewSubmission::class);
        Event::listen(StudentProgressCreated::class, NotifyTeacherProgressUpdated::class);
        Event::listen(MemorizationAssignmentCreated::class, NotifyStudentNewAssignment::class);

        Gate::policy(StudentProgress::class, StudentProgressPolicy::class);
        Gate::policy(StudentProfile::class, StudentProfilePolicy::class);
        Gate::policy(StudentSubmission::class, StudentSubmissionPolicy::class);
        Gate::policy(Circle::class, CirclePolicy::class);
        Gate::policy(TeacherProfile::class, TeacherProfilePolicy::class);
        Gate::policy(MemorizationAssignment::class, MemorizationAssignmentPolicy::class);
        Gate::policy(MemorizationSession::class, MemorizationSessionPolicy::class);

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip());
        });

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
