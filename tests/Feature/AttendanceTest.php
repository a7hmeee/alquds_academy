<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\CircleSession;
use App\Models\AttendanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Circle $circle;
    private StudentProfile $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super admin');
        $this->actingAs($this->admin);

        $this->circle = Circle::factory()->create(['status' => 'active']);
        $this->student = StudentProfile::factory()->create();
        $this->circle->students()->attach($this->student->id, ['status' => 'active']);
    }

    public function test_admin_can_create_circle_session(): void
    {
        $response = $this->post(route('circle-sessions.store', $this->circle), [
            'title' => 'جلسة مراجعة',
            'session_date' => now()->format('Y-m-d'),
            'session_type' => 'regular',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('circle_sessions', [
            'circle_id' => $this->circle->id,
            'title' => 'جلسة مراجعة',
        ]);
    }

    public function test_admin_can_save_attendance(): void
    {
        $session = CircleSession::factory()->create(['circle_id' => $this->circle->id]);

        $response = $this->post(route('circle-sessions.attendance.save', [$this->circle, $session]), [
            'attendance' => [
                $this->student->id => [
                    'status' => 'present',
                    'note' => 'ملتزم',
                ],
            ],
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendance_records', [
            'circle_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'present',
        ]);
    }

    public function test_session_shows_attendance_stats(): void
    {
        $session = CircleSession::factory()->create(['circle_id' => $this->circle->id]);
        AttendanceRecord::factory()->create([
            'circle_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'present',
        ]);

        $response = $this->get(route('circle-sessions.show', [$this->circle, $session]));
        $response->assertOk();
        $response->assertSee('حاضر');
    }

    public function test_attendance_updates_existing_record(): void
    {
        $session = CircleSession::factory()->create(['circle_id' => $this->circle->id]);
        AttendanceRecord::factory()->create([
            'circle_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'present',
        ]);

        $this->post(route('circle-sessions.attendance.save', [$this->circle, $session]), [
            'attendance' => [
                $this->student->id => ['status' => 'absent', 'note' => ''],
            ],
        ]);

        $this->assertDatabaseHas('attendance_records', [
            'circle_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'absent',
        ]);
        $this->assertEquals(1, AttendanceRecord::where('circle_session_id', $session->id)->count());
    }
}
