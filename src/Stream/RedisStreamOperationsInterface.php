<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Stream;

/**
 * move this logic in LLegaz\Redis\RedisClientInterface
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
interface RedisStreamOperationsInterface
{
    /**
    * @param string $key
     * @return int
     */
    public function example($key): int;
}
