<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentProfile extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'phone', 'occupation', 'relationship',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(StudentProfile::class, 'parent_student', 'parent_profile_id', 'student_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }
}
