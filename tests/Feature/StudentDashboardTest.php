<?php

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Circle;
use App\Models\CircleStudent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'student', 'guard_name' => 'web']);
});

test('student dashboard returns 200 for authorized student', function () {
    $user = User::create([
        'name' => 'Test Student',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole('student');

    $circle = Circle::create(['name' => 'Test Circle']);
    $student = StudentProfile::create(['user_id' => $user->id, 'full_name' => 'Test Student']);
    CircleStudent::create(['circle_id' => $circle->id, 'student_id' => $student->id, 'status' => 'active']);

    $response = $this->actingAs($user)->get(route('student.dashboard'));
    $response->assertStatus(200);
});

test('recordings upload route is resolvable without exception', function () {
    $url = route('recordings.upload');
    expect($url)->not->toBeNull();
    expect($url)->toContain('student/recordings/upload');
});
