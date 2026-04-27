<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\List;

/**
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
interface RedisListOperationsInterface
{
    /**
    * @param string $key
     * @return int
     */
    public function example($key): int;
}
