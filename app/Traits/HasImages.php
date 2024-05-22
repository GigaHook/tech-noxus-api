<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Для моделей, которые имеют изображения
 */
trait HasImages
{
    public function storeImage(UploadedFile $imageFile): void
    {
        $this->update(['image' => $imageFile->store(options: 'public')]);
    }

    public function deleteImage(): void
    {
        Storage::disk('public')->delete($this->image);
    }

    public function updateImage(UploadedFile $imageFile): void
    {
        $this->deleteImage();
        $this->storeImage($imageFile);
    }
}