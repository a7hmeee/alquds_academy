<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentAchievement;
use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private StudentProfile $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super admin');
        $this->actingAs($this->admin);

        $this->student = StudentProfile::factory()->create();
    }

    public function test_admin_can_view_achievements(): void
    {
        StudentAchievement::factory()->create(['student_id' => $this->student->id]);
        $response = $this->get(route('achievements.index'));
        $response->assertOk();
    }

    public function test_admin_can_view_certificates(): void
    {
        Certificate::factory()->create(['student_id' => $this->student->id]);
        $response = $this->get(route('certificates.index'));
        $response->assertOk();
    }

    public function test_certificate_has_verification_code(): void
    {
        $certificate = Certificate::factory()->create([
            'student_id' => $this->student->id,
            'verification_code' => 'CERT-' . strtoupper(uniqid()),
        ]);
        $this->assertNotNull($certificate->verification_code);
    }
}
