<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductImageService
{
    public function store(UploadedFile $image): string
    {
        File::ensureDirectoryExists(public_path('products'));

        $extension = strtolower($image->getClientOriginalExtension());

        $imageName = now()->timestamp . '_' . Str::uuid() . '.' . $extension;

        $image->move(public_path('products'), $imageName);

        return $imageName;
    }

    public function delete(?string $imageName): void
    {
        if (!$imageName) {
            return;
        }

        $imagePath = public_path('products/' . $imageName);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    public function replace(?string $oldImageName, UploadedFile $newImage): string
    {
        $this->delete($oldImageName);

        return $this->store($newImage);
    }
}