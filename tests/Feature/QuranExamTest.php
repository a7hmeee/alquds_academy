<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\QuranExam;
use App\Models\QuranExamResult;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuranExamTest extends TestCase
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

    public function test_admin_can_create_exam(): void
    {
        $response = $this->post(route('quran-exams.store'), [
            'circle_id' => $this->circle->id,
            'title' => 'اختبار سورة البقرة',
            'exam_type' => 'surah',
            'exam_date' => now()->format('Y-m-d'),
            'total_score' => 100,
            'passing_score' => 70,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('quran_exams', ['title' => 'اختبار سورة البقرة']);
    }

    public function test_admin_can_add_result(): void
    {
        $exam = QuranExam::factory()->create([
            'circle_id' => $this->circle->id,
            'total_score' => 100,
            'passing_score' => 70,
        ]);

        $response = $this->post(route('quran-exams.results.store', $exam), [
            'student_id' => $this->student->id,
            'score' => 85,
            'status' => 'completed',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('quran_exam_results', [
            'quran_exam_id' => $exam->id,
            'student_id' => $this->student->id,
            'passed' => true,
        ]);
    }

    public function test_result_calculates_percentage_and_pass(): void
    {
        $exam = QuranExam::factory()->create(['total_score' => 100, 'passing_score' => 70]);

        $result = QuranExamResult::factory()->create([
            'quran_exam_id' => $exam->id,
            'score' => 50,
            'status' => 'completed',
        ]);

        $this->assertEquals(50.0, $result->percentage);
        $this->assertFalse($result->passed);
    }

    public function test_exam_shows_results_count(): void
    {
        $exam = QuranExam::factory()->create(['circle_id' => $this->circle->id]);
        QuranExamResult::factory()->count(3)->create(['quran_exam_id' => $exam->id]);

        $response = $this->get(route('quran-exams.show', $exam));
        $response->assertOk();
        $this->assertEquals(3, $exam->fresh()->results->count());
    }
}
