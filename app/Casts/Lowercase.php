<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lowercase implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return static::runCast($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return static::runCast($value);
    }

    /**
     * Run cast logic
     *
     * @param string|null $value
     * 
     * @return string|null
     */
    public static function runCast(?string $value): ?string
    {
        return ! is_null($value) && is_string($value)
               ? Str::of($value)->lower()->trim()->toString()
               : null;
    }
}
