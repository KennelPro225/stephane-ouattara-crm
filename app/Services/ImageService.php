<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function store(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid().'.'.$file->extension();

        return $file->storeAs($directory, $filename, 'public');
    }

    public function replace(?string $existingPath, UploadedFile $file, string $directory): string
    {
        $this->delete($existingPath);

        return $this->store($file, $directory);
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
