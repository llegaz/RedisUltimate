<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Exception;

class InvalidKeyException extends InvalidArgumentException
{
    public function __construct(string $message = 'RedisUltimate says "Can\'t do shit with this Key"' . PHP_EOL, int $code = 400, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
