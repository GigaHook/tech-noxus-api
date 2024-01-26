<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileService 
{
    public function uploadImage(UploadedFile $image): string
    {
        $name = Str::uuid().'.'.$image->getClientOriginalExtension();
        Storage::disk('public')->put($name, file_get_contents($image));
        return $name;
    }

    public function deleteImage(string $image): void
    {
        Storage::disk('public')->delete($image);
        //вроде всё
    }
}