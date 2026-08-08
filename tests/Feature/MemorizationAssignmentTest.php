<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\MemorizationAssignment;
use App\Models\Surah;
use App\Models\Juz;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemorizationAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $teacher;
    private User $student;
    private Circle $circle;
    private StudentProfile $studentProfile;
    private TeacherProfile $teacherProfile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\NewPermissionsSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super admin');

        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('teacher');
        $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

        $this->student = User::factory()->create();
        $this->student->assignRole('student');
        $this->studentProfile = StudentProfile::factory()->create(['user_id' => $this->student->id]);

        $this->circle = Circle::factory()->create(['status' => 'active']);
        $this->circle->teachers()->attach($this->teacherProfile->id, ['status' => 'active']);
        $this->circle->students()->attach($this->studentProfile->id, ['status' => 'active']);
    }

    public function test_admin_can_view_assignments_index(): void
    {
        $this->actingAs($this->admin);
        MemorizationAssignment::factory()->create([
            'student_id' => $this->studentProfile->id,
            'teacher_id' => $this->teacherProfile->id,
            'circle_id' => $this->circle->id,
        ]);

        $response = $this->get(route('memorization-assignments.index'));
        $response->assertOk();
    }

    public function test_admin_can_create_assignment(): void
    {
        $this->actingAs($this->admin);
        $surah = Surah::factory()->create();

        $response = $this->post(route('memorization-assignments.store'), [
            'student_id' => $this->studentProfile->id,
            'circle_id' => $this->circle->id,
            'assignment_type' => 'new_memorization',
            'surah_id' => $surah->id,
            'ayah_from' => 1,
            'ayah_to' => 10,
            'priority' => 'medium',
            'status' => 'assigned',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('memorization_assignments', [
            'student_id' => $this->studentProfile->id,
            'circle_id' => $this->circle->id,
        ]);
    }

    public function test_status_transition_guard_blocks_invalid_transition(): void
    {
        $this->actingAs($this->admin);
        $surah = Surah::factory()->create();

        $assignment = MemorizationAssignment::factory()->create([
            'student_id' => $this->studentProfile->id,
            'teacher_id' => $this->teacherProfile->id,
            'circle_id' => $this->circle->id,
            'status' => 'assigned',
        ]);

        $response = $this->patch(route('memorization-assignments.status', $assignment), [
            'status' => 'completed',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_teacher_can_create_assignment_for_own_circle(): void
    {
        $this->actingAs($this->teacher);
        $surah = Surah::factory()->create();

        $response = $this->post(route('memorization-assignments.store'), [
            'student_id' => $this->studentProfile->id,
            'circle_id' => $this->circle->id,
            'assignment_type' => 'new_memorization',
            'surah_id' => $surah->id,
            'ayah_from' => 1,
            'ayah_to' => 10,
        ]);

        $response->assertSessionHas('success');
    }

    public function test_student_cannot_access_admin_assignments(): void
    {
        $this->actingAs($this->student);
        $response = $this->get(route('memorization-assignments.index'));
        $response->assertForbidden();
    }
}
