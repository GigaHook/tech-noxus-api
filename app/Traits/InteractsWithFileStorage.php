<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait InteractsWithFileStorage
{
    function storeImage(UploadedFile $image): string
    {
        return basename($image->storePubliclyAs('images', Str::uuid().'.'.$image->extension()));
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