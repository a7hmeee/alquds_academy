<?php

namespace App\Actions\Students;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentProgress;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class CreateStudentAction
{
    public function execute(array $validated, $photoFile): StudentProfile
    {
        $photoPath = null;

        try {
            $photoPath = $photoFile
                ? FileUploadService::uploadImage($photoFile, 'students')
                : null;

            $student = DB::transaction(function () use ($validated, $photoPath) {
                if (!empty($validated['user_id'])) {
                    $userId = $validated['user_id'];
                } elseif (!empty($validated['email'])) {
                    $user = User::create([
                        'name'     => $validated['full_name'],
                        'email'    => $validated['email'],
                        'password' => Hash::make($validated['password'] ?? '12345678'),
                    ]);
                    $userId = $user->id;
                } else {
                    $userId = null;
                }

                $student = StudentProfile::create([
                    'user_id'    => $userId,
                    'full_name'  => $validated['full_name'],
                    'phone'      => $validated['phone'] ?? null,
                    'birth_date' => $validated['birth_date'] ?? null,
                    'gender'     => $validated['gender'] ?? null,
                    'guardian_name'  => $validated['guardian_name'] ?? null,
                    'guardian_phone' => $validated['guardian_phone'] ?? null,
                    'photo'      => $photoPath,
                    'status'     => $validated['status'],
                    'notes'      => $validated['notes'] ?? null,
                    'memorization_level' => $validated['memorization_level'] ?? null,
                    'tajweed_level'      => $validated['tajweed_level'] ?? null,
                    'current_surah' => $validated['surah_id'] ?? null,
                    'current_juz'   => $validated['juz_id'] ?? null,
                    'current_ayah'  => $validated['ayah'] ?? null,
                    'is_smart_mode'  => $validated['is_smart_mode'] ?? false,
                    'needs_assistance' => $validated['needs_assistance'] ?? false,
                ]);

                if (!empty($validated['memorization_level']) && $validated['memorization_level'] !== 'لا يحفظ') {
                    StudentProgress::create([
                        'student_id' => $student->id,
                        'surah_id'   => $validated['surah_id'],
                        'juz_id'     => $validated['juz_id'],
                        'ayah'       => $validated['ayah'],
                        'notes'      => $validated['notes'] ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }

                return $student;
            });

            return $student;
        } catch (Throwable $e) {
            if ($photoPath) {
                FileUploadService::deleteFile($photoPath);
            }
            throw $e;
        }
    }
}
