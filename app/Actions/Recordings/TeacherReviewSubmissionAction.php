<?php

namespace App\Actions\Recordings;

use App\Models\StudentSubmission;
use App\Services\JuzProgressService;

class TeacherReviewSubmissionAction
{
    public function execute(StudentSubmission $submission, array $data, int $reviewedBy): StudentSubmission
    {
        $submission->update([
            'status' => $data['status'],
            'review_notes' => $data['review_notes'],
            'rating' => $data['rating'] ?? null,
            'score' => $data['score'],
            'reviewed_by' => $reviewedBy,
        ]);

        JuzProgressService::clearStudentCache(
            $submission->student_id,
            $submission->juz_id
        );

        return $submission->fresh();
    }
}
