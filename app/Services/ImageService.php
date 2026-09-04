<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public const DIRECTORIES = ['programmes', 'testimonials', 'content'];

    public function store(UploadedFile $file, string $directory = 'content'): string
    {
        // Assainir le répertoire (lettres, chiffres, tirets et slashes simples)
        $directory = trim(preg_replace('/[^a-z0-9\-\/]/', '', strtolower($directory)), '/');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->storePubliclyAs($directory, $filename, 'public');

        return "{$directory}/{$filename}";
    }

    public function replace(?string $oldPath, UploadedFile $file, string $directory = 'content'): string
    {
        if ($oldPath) {
            $this->delete($oldPath);
        }

        return $this->store($file, $directory);
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function url(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
