<?php

namespace App\Actions\Teachers;

use App\Models\User;
use App\Models\TeacherProfile;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateTeacherAction
{
    public function execute(array $validated, $photoFile): TeacherProfile
    {
        $photoPath = null;

        try {
            if ($photoFile) {
                $photoPath = FileUploadService::uploadImage($photoFile, 'teachers');
            }

            $teacher = DB::transaction(function () use ($validated, $photoPath) {
                if (!empty($validated['user_id'])) {
                    $user = User::findOrFail($validated['user_id']);
                } else {
                    $user = User::create([
                        'name'     => $validated['name'],
                        'email'    => $validated['email'],
                        'password' => bcrypt($validated['password']),
                    ]);
                    $user->assignRole('teacher');
                }

                return TeacherProfile::create([
                    'user_id'         => $user->id,
                    'academic_degree' => $validated['academic_degree'],
                    'photo'           => $photoPath,
                    'bio'             => $validated['bio'] ?? null,
                ]);
            });

            return $teacher;
        } catch (Throwable $e) {
            if ($photoPath) {
                FileUploadService::deleteFile($photoPath);
            }
            throw $e;
        }
    }
}
