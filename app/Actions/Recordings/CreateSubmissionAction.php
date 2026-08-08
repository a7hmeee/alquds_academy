<?php

namespace App\Actions\Recordings;

use App\Models\StudentSubmission;
use App\DTOs\Recordings\SubmissionData;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use App\Services\JuzProgressService;
use Throwable;

class CreateSubmissionAction
{
    /**
     * Execute the submission creation process.
     *
     * Flow:
     * 1. Upload audio file (safe — outside transaction)
     * 2. Upload image file if present (safe — outside transaction)
     * 3. Create StudentSubmission in DB transaction
     * 4. Fire event (event fires before cache invalidation so a cache
     *    failure never suppresses teacher notification)
     * 5. Invalidate juz progress cache (non-critical)
     * 6. If DB fails, rollback uploaded files
     * 7. Return the created submission
     */
    public function execute(SubmissionData $data): StudentSubmission
    {
        $audioPath = null;
        $imagePath = null;

        try {
            // 1. Upload files first (outside transaction)
            $audioPath = FileUploadService::uploadAudio($data->audioFile, $data->audioUploadPath);

            if ($data->imageFile) {
                $imagePath = FileUploadService::uploadImage($data->imageFile, $data->imageUploadPath);
            }

            // 2. Create submission in transaction
            $submission = DB::transaction(function () use ($data, $audioPath, $imagePath) {
                $insert = [
                    'student_id' => $data->studentId,
                    'circle_id' => $data->circleId,
                    'file_path' => $audioPath,
                    'image_path' => $imagePath,
                    'surah' => $data->surahName,
                    'ayah' => $data->ayah ?? ($data->ayahFrom ? (string) $data->ayahFrom : null),
                    'juz' => $data->juzName,
                    'surah_id' => $data->surahId,
                    'juz_id' => $data->juzId,
                    'ayah_from' => $data->ayahFrom,
                    'ayah_to' => $data->ayahTo,
                    'notes' => $data->notes,
                    'status' => 'pending',
                ];

                return StudentSubmission::create($insert);
            });

            // 3. Fire event BEFORE cache invalidation so a cache failure
            //    never suppresses the teacher notification.
            event(new \App\Events\SubmissionCreated($submission));

            // 4. Invalidate progress cache for the student's juz (non-critical)
            if ($data->juzId) {
                JuzProgressService::clearStudentCache($data->studentId, $data->juzId);
            }

            return $submission;

        } catch (Throwable $e) {
            // Rollback: clean up uploaded files if DB insert failed
            if ($audioPath) {
                FileUploadService::deleteFile($audioPath);
            }
            if ($imagePath) {
                FileUploadService::deleteFile($imagePath);
            }

            throw $e;
        }
    }
}
