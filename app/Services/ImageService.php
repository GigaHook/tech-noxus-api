<?php

namespace App\Services;

use App\Contracts\Imageable;
use App\Models\Image;

/**
 * Отвечает за хранения данных о изображении в бд
 */
class ImageService
{
    public function create(string $name, Imageable $imageable): void
    {
        $imageable->images()->create([
            'name' => $name,
        ]);
    }

    public function delete(string $name): void
    {
        Image::firstWhere('name', $name)->delete();
    }
}