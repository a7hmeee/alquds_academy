<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private const ALLOWED_AUDIO_MIMES = ['audio/mpeg', 'audio/wav', 'audio/x-wav', 'audio/mp4', 'audio/ogg', 'video/mp4'];
    private const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp'];
    private const ALLOWED_DOC_MIMES = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

    public static function validateAudio(UploadedFile $file): bool
    {
        $mime = $file->getMimeType();
        return in_array($mime, self::ALLOWED_AUDIO_MIMES);
    }

    public static function validateImage(UploadedFile $file): bool
    {
        $mime = $file->getMimeType();
        return in_array($mime, self::ALLOWED_IMAGE_MIMES);
    }

    public static function validateDocument(UploadedFile $file): bool
    {
        $mime = $file->getMimeType();
        return in_array($mime, self::ALLOWED_DOC_MIMES);
    }

    public static function uploadAudio(UploadedFile $file, string $path = 'recordings/audio'): string
    {
        if (!self::validateAudio($file)) {
            throw new \InvalidArgumentException('نوع الملف الصوتي غير مسموح به');
        }

        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $filename, 'public');
    }

    public static function uploadImage(UploadedFile $file, string $path = 'recordings/images'): string
    {
        if (!self::validateImage($file)) {
            throw new \InvalidArgumentException('نوع ملف الصورة غير مسموح به');
        }

        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $filename, 'public');
    }

    public static function deleteFile(?string $path): bool
    {
        if (!$path) {
            return false;
        }
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
