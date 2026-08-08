<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\ParentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParentPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $parentUser;
    private StudentProfile $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parentUser = User::factory()->create();
        $this->parentUser->assignRole('parent');

        $parentProfile = ParentProfile::factory()->create(['user_id' => $this->parentUser->id]);
        $this->student = StudentProfile::factory()->create();
        $parentProfile->students()->attach($this->student->id);
    }

    public function test_parent_can_view_dashboard(): void
    {
        $this->actingAs($this->parentUser);
        $response = $this->get(route('parent.dashboard'));
        $response->assertOk();
    }

    public function test_parent_can_view_student_progress(): void
    {
        $this->actingAs($this->parentUser);
        $response = $this->get(route('parent.student.progress', $this->student));
        $response->assertOk();
    }

    public function test_parent_cannot_view_other_student(): void
    {
        $this->actingAs($this->parentUser);
        $otherStudent = StudentProfile::factory()->create();
        $response = $this->get(route('parent.student.progress', $otherStudent));
        $response->assertForbidden();
    }
}
