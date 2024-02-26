<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Отвечает за хранение и управление файлами изображений
 */
trait HasMedia
{
    function storeImage(UploadedFile $image): string
    {
        return basename($image->storePubliclyAs('public/images', Str::uuid().'.'.$image->extension()));
    }

    function deleteImage(string $image): void
    {
        Storage::delete('images/'.$image);
    }   

    function updateImage(UploadedFile $newImage, string $oldImage): string
    {
        $this->deleteImage($oldImage);
        return $this->storeImage($newImage);
    }
}