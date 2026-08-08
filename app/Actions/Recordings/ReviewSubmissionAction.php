<?php

namespace App\Actions\Recordings;

use App\Models\StudentSubmission;
use App\Services\JuzProgressService;

class ReviewSubmissionAction
{
    /**
     * Update a submission's self-rating and notes.
     */
    public function execute(StudentSubmission $submission, ?int $selfRating, ?string $notes): StudentSubmission
    {
        $submission->update([
            'self_rating' => $selfRating,
            'self_notes' => $notes,
        ]);

        // Clear progress cache for the student's juz
        JuzProgressService::clearStudentCache(
            $submission->student_id,
            $submission->juz_id
        );

        return $submission->fresh();
    }
}
