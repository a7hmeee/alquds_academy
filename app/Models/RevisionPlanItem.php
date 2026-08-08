<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionPlanItem extends Model
{
    protected $fillable = [
        'revision_plan_id', 'assignment_type', 'surah_id', 'juz_id',
        'ayah_from', 'ayah_to', 'scheduled_date', 'repetition_target',
        'status', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'repetition_target' => 'integer',
            'completed_at' => 'datetime',
            'ayah_from' => 'integer',
            'ayah_to' => 'integer',
        ];
    }

    public function plan()
    {
        return $this->belongsTo(RevisionPlan::class, 'revision_plan_id');
    }

    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }

    public function juz()
    {
        return $this->belongsTo(Juz::class);
    }
}
