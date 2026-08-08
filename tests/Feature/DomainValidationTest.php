<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Services\DomainValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_belongs_to_circle_returns_true(): void
    {
        $circle = Circle::factory()->create();
        $student = StudentProfile::factory()->create();
        $circle->students()->attach($student->id, ['status' => 'active']);

        $this->assertTrue(DomainValidationService::studentBelongsToCircle($student->id, $circle->id));
    }

    public function test_student_belongs_to_circle_returns_false(): void
    {
        $circle = Circle::factory()->create();
        $student = StudentProfile::factory()->create();

        $this->assertFalse(DomainValidationService::studentBelongsToCircle($student->id, $circle->id));
    }

    public function test_teacher_belongs_to_circle_returns_true(): void
    {
        $circle = Circle::factory()->create();
        $teacher = TeacherProfile::factory()->create();
        $circle->teachers()->attach($teacher->id, ['status' => 'active']);

        $this->assertTrue(DomainValidationService::teacherBelongsToCircle($teacher->id, $circle->id));
    }

    public function test_submission_is_valid_checks_both(): void
    {
        $circle = Circle::factory()->create();
        $student = StudentProfile::factory()->create();
        $teacher = TeacherProfile::factory()->create();
        $circle->students()->attach($student->id, ['status' => 'active']);
        $circle->teachers()->attach($teacher->id, ['status' => 'active']);

        $this->assertTrue(DomainValidationService::submissionIsValid($student->id, $circle->id, $teacher->id));
        $this->assertFalse(DomainValidationService::submissionIsValid(999, $circle->id, $teacher->id));
    }

    public function test_get_student_primary_teacher(): void
    {
        $circle = Circle::factory()->create();
        $student = StudentProfile::factory()->create();
        $teacher = TeacherProfile::factory()->create();
        $circle->students()->attach($student->id, ['status' => 'active']);
        $circle->teachers()->attach($teacher->id, ['status' => 'active', 'role' => 'primary']);

        $primary = DomainValidationService::getStudentPrimaryTeacher($student->id);
        $this->assertNotNull($primary);
        $this->assertEquals($teacher->id, $primary->id);
    }
}
