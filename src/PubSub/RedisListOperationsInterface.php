<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\PubSub;

/**
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
