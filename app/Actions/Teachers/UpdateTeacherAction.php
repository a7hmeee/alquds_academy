<?php

namespace App\Actions\Teachers;

use App\Models\TeacherProfile;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateTeacherAction
{
    public function execute(TeacherProfile $teacher, array $validated, $photoFile): TeacherProfile
    {
        $oldPhoto = $teacher->photo;
        $newPhotoPath = null;

        try {
            if ($photoFile) {
                $newPhotoPath = FileUploadService::uploadImage($photoFile, 'teachers');
            }

            DB::transaction(function () use ($teacher, $validated, $newPhotoPath) {
                $updateData = [
                    'academic_degree'    => $validated['academic_degree'],
                    'years_of_experience'=> $validated['years_of_experience'] ?? null,
                    'specialization'     => $validated['specialization'] ?? null,
                    'riwayat'            => $validated['riwayat'] ?? null,
                    'teaching_language'  => $validated['teaching_language'] ?? null,
                    'gender'             => $validated['gender'] ?? null,
                    'bio'                => $validated['bio'] ?? null,
                    'status'             => $validated['status'],
                ];

                if ($newPhotoPath) {
                    $updateData['photo'] = $newPhotoPath;
                }

                $teacher->update($updateData);
            });

            if ($newPhotoPath && $oldPhoto) {
                FileUploadService::deleteFile($oldPhoto);
            }

            return $teacher->fresh();
        } catch (Throwable $e) {
            if ($newPhotoPath) {
                FileUploadService::deleteFile($newPhotoPath);
            }
            throw $e;
        }
    }
}
