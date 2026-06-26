<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ProductImageService
{
    public function store(UploadedFile $image): string
    {
        $imageName = time()
            . '_'
            . uniqid()
            . '.'
            . $image->getClientOriginalExtension();

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

    public function replace(
        ?string $oldImageName,
        UploadedFile $newImage
    ): string {
        $this->delete($oldImageName);

        return $this->store($newImage);
    }
}