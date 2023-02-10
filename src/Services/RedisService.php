<?php

namespace App\Services;

use RedisException;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisService
{
    public const REDIS_DB = 0;

    public const HALF_DAY_IN_SECONDS = 43200; // 60 * 60 * 12

    private $redis;

    private JsonConvertService $jsonService;

    public function __construct(JsonConvertService $jsonService)
    {
        $this->redis = RedisAdapter::createConnection('redis://localhost');
        $this->jsonService = $jsonService;

        $this->redis->select(self::REDIS_DB);
    }

    public function set(string $key, mixed $value, int $seconds = self::HALF_DAY_IN_SECONDS): bool
    {
        try {
            $this->redis->setex($key, $seconds, $value);
        } catch (RedisException) {
            return false;
        }

        return true;
    }

    public function get(string $key): mixed
    {
        try {
            $result = $this->redis->get($key);
        } catch (RedisException) {
            $result = false;
        }

        return $result;
    }

    public function getOrSave(
        string $key,
        callable $function,
        int $seconds = self::HALF_DAY_IN_SECONDS
    ): mixed
    {
        $value = $this->get($key);

        if (empty($value)) {
            $value = $function();
            $this->set($key, $value, $seconds);
        }

        return $value;
    }

    public function setWithJsonEncode(
        string $key,
        mixed $value,
        int $seconds = self::HALF_DAY_IN_SECONDS
    ): void
    {
        $value = $this->jsonService->encodeToJson($value);

        $this->set($key, $value, $seconds);
    }

    public function getWithJsonDecode(string $key, bool $isAssociative = true): mixed
    {
        return $this->jsonService->decodeFromJson($this->get($key), $isAssociative);
    }

    public function getOrSaveWithJsonConvert(
        string   $key,
        callable $function,
        int      $seconds = self::HALF_DAY_IN_SECONDS,
        bool     $isAssociative = true
    ): mixed
    {
        $valueJsonFunction = function () use ($function) {
            return $this->jsonService->encodeToJson($function());
        };

        return $this->jsonService->decodeFromJson(
            $this->getOrSave($key, $valueJsonFunction, $seconds),
            $isAssociative
        );
    }

    public function delete(string $key): int
    {
        try {
            $result = $this->redis->del($key);
        } catch (RedisException) {
            $result = 0;
        }

        return $result;
    }
}
