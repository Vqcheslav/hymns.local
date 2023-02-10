<?php

namespace App\Services;

use JsonException;

class JsonConvertService
{
    public function encodeToJson(mixed $value): string|bool
    {
        try {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $value = '';
        }

        return $value;
    }

    public function decodeFromJson(mixed $value, bool $isAssociative): mixed
    {
        try {
            $value = json_decode($value, $isAssociative, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $value = '';
        }

        return $value;
    }

    public function decodeStringFromJsonArray(mixed $value): string
    {
        return implode(', ', $this->decodeFromJson($value, true));
    }
}
