<?php

namespace App\Actions\Students;

use App\Models\StudentProfile;
use App\Models\StudentProgress;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateStudentAction
{
    public function execute(StudentProfile $student, array $validated, $photoFile): StudentProfile
    {
        $oldPhoto = $student->photo;
        $newPhotoPath = null;

        try {
            if ($photoFile) {
                $newPhotoPath = FileUploadService::uploadImage($photoFile, 'students');
            }

            DB::transaction(function () use ($student, $validated, $newPhotoPath) {
                $updateData = [
                    'full_name' => $validated['full_name'],
                    'phone'     => $validated['phone'],
                    'teacher_id' => $validated['teacher_id'] ?? $student->teacher_id,
                    'status'    => $validated['status'],
                    'notes'     => $validated['notes'],
                    'memorization_level' => $validated['memorization_level'],
                    'tajweed_level'      => $validated['tajweed_level'],
                ];

                if ($newPhotoPath) {
                    $updateData['photo'] = $newPhotoPath;
                }

                $student->update($updateData);

                if (!empty($validated['memorization_level']) && $validated['memorization_level'] !== 'لا يحفظ') {
                    StudentProgress::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'circle_id' => $student->circleStudents()->first()?->circle_id,
                        ],
                        [
                            'surah_id' => $validated['surah_id'],
                            'juz_id'   => $validated['juz_id'],
                            'ayah'     => $validated['ayah'],
                            'notes'    => $validated['notes'] ?? null,
                            'teacher_id' => $validated['teacher_id'],
                            'created_by' => auth()->id(),
                        ]
                    );

                    $student->update([
                        'current_surah' => $validated['surah_id'],
                        'current_juz'   => $validated['juz_id'],
                        'current_ayah'  => $validated['ayah'],
                    ]);
                }
            });

            if ($newPhotoPath && $oldPhoto) {
                FileUploadService::deleteFile($oldPhoto);
            }

            return $student->fresh();
        } catch (Throwable $e) {
            if ($newPhotoPath) {
                FileUploadService::deleteFile($newPhotoPath);
            }
            throw $e;
        }
    }
}
