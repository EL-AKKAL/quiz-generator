<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PictureService
{
    /**
     * Store a picture and delete the old one if it exists.
     */
    public static function store(?UploadedFile $file, ?string $oldPath = null, string $directory = 'uploads', string $disk = 'public'): ?string
    {
        if (!$file instanceof UploadedFile)
            return $oldPath;

        if ($oldPath && Storage::disk($disk)->exists($oldPath))
            Storage::disk($disk)->delete($oldPath);

        return $file->store($directory, $disk);
    }

    /**
     * Delete a picture from storage.
     */
    public static function delete(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path))
            Storage::disk($disk)->delete($path);
    }
}
