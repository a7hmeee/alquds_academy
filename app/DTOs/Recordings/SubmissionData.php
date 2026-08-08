<?php

namespace App\DTOs\Recordings;

use Illuminate\Http\UploadedFile;

class SubmissionData
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $circleId,
        public readonly ?int $surahId,
        public readonly ?int $juzId,
        public readonly ?int $ayahFrom,
        public readonly ?int $ayahTo,
        public readonly UploadedFile $audioFile,
        public readonly ?UploadedFile $imageFile,
        public readonly ?string $notes,
        public readonly ?string $surahName,
        public readonly ?string $juzName,
        public readonly ?string $ayah = null,
        public readonly string $audioUploadPath = 'recordings/audio',
        public readonly string $imageUploadPath = 'recordings/images',
    ) {}

    public static function fromStoreSubmissionRequest(array $validated, int $studentId, \App\Models\Surah $surah, \App\Models\Juz $juz, ?\Illuminate\Http\UploadedFile $audio, ?\Illuminate\Http\UploadedFile $image, string $audioPath = 'recordings/audio', string $imagePath = 'recordings/images'): self
    {
        return new self(
            studentId: $studentId,
            circleId: (int) $validated['circle_id'],
            surahId: (int) $validated['surah_id'],
            juzId: (int) $validated['juz_id'],
            ayahFrom: (int) $validated['ayah_from'],
            ayahTo: isset($validated['ayah_to']) ? (int) $validated['ayah_to'] : (int) $validated['ayah_from'],
            audioFile: $audio,
            imageFile: $image,
            notes: $validated['notes'] ?? null,
            surahName: $surah->name_ar,
            juzName: $juz->name ?? $juz->id,
            audioUploadPath: $audioPath,
            imageUploadPath: $imagePath,
        );
    }

    public static function fromFeatureRequest(array $validated, int $studentId, int $circleId, UploadedFile $audio, ?UploadedFile $image, string $audioPath = 'submissions/audio', string $imagePath = 'submissions/images'): self
    {
        return new self(
            studentId: $studentId,
            circleId: $circleId,
            surahId: isset($validated['surah_id']) ? (int) $validated['surah_id'] : null,
            juzId: isset($validated['juz_id']) ? (int) $validated['juz_id'] : null,
            ayahFrom: isset($validated['ayah_from']) ? (int) $validated['ayah_from'] : (isset($validated['ayah']) ? (int) $validated['ayah'] : null),
            ayahTo: isset($validated['ayah_to']) ? (int) $validated['ayah_to'] : null,
            audioFile: $audio,
            imageFile: $image,
            notes: $validated['notes'] ?? null,
            surahName: $validated['surah'] ?? null,
            juzName: $validated['juz'] ?? null,
            ayah: isset($validated['ayah']) ? (string) $validated['ayah'] : null,
            audioUploadPath: $audioPath,
            imageUploadPath: $imagePath,
        );
    }
}
