<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\RevisionPlan;
use App\Models\RevisionPlanItem;
use App\Models\Surah;
use App\Models\Juz;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevisionPlanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Circle $circle;
    private StudentProfile $student;
    private Surah $surah;
    private Juz $juz;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super admin');
        $this->actingAs($this->admin);

        $this->circle = Circle::factory()->create(['status' => 'active']);
        $this->student = StudentProfile::factory()->create();
        $this->circle->students()->attach($this->student->id, ['status' => 'active']);
        $this->surah = Surah::factory()->create();
        $this->juz = Juz::factory()->create();
    }

    public function test_admin_can_create_revision_plan(): void
    {
        $response = $this->post(route('revision-plans.store'), [
            'student_id' => $this->student->id,
            'circle_id' => $this->circle->id,
            'name' => 'خطة مراجعة الجزء ١',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
            'items' => [
                [
                    'assignment_type' => 'close_revision',
                    'surah_id' => $this->surah->id,
                    'juz_id' => $this->juz->id,
                    'ayah_from' => 1,
                    'ayah_to' => 10,
                ],
            ],
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('revision_plans', ['name' => 'خطة مراجعة الجزء ١']);
        $this->assertDatabaseHas('revision_plan_items', ['ayah_from' => 1, 'ayah_to' => 10]);
    }

    public function test_completing_all_items_completes_plan(): void
    {
        $plan = RevisionPlan::factory()->create(['status' => 'active']);
        $item = RevisionPlanItem::factory()->create([
            'revision_plan_id' => $plan->id,
            'status' => 'pending',
        ]);

        $response = $this->patch(route('revision-plans.items.complete', $item));
        $response->assertSessionHas('success');

        $this->assertEquals('completed', $item->fresh()->status);
        $this->assertEquals('completed', $plan->fresh()->status);
    }

    public function test_plan_shows_progress(): void
    {
        $plan = RevisionPlan::factory()->create(['status' => 'active']);
        RevisionPlanItem::factory()->count(3)->create(['revision_plan_id' => $plan->id, 'status' => 'pending']);
        RevisionPlanItem::factory()->count(2)->create(['revision_plan_id' => $plan->id, 'status' => 'completed']);

        $response = $this->get(route('revision-plans.show', $plan));
        $response->assertOk();
    }
}
