<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\PubSub;

/**
 * move this logic in LLegaz\Redis\RedisClientInterface
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
interface RedisPubSubOperationsInterface
{
    /**
    * @param string $key
     * @return int
     */
    public function example($key): int;
}
