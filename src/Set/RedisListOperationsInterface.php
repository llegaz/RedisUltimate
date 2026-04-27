<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Set;

/**
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
interface RedisSetOperationsInterface
{
    /**
    * @param string $key
     * @return int
     */
    public function example($key): int;
}
