<?php

namespace App\Contracts;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Модель должна иметь связь с моделью Image
 */
interface Imageable
{
    public function images(): MorphMany;
}