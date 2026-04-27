<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Tests\Unit;

use LLegaz\Redis\Tests\RedisAdapterTestBase;
use LLegaz\Redis\RedisClientInterface;
/**
 *
 *
 *
 * @todo implement this
 *
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
class RedisUltimateTest extends RedisAdapterTestBase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject&RedisClientInterface */
    protected RedisClientInterface $predisClient;

    public function testGet()
    {
        $this->assertFalse(false);
    }

    protected function getSelfClient(): RedisClientInterface {
        return $this->predisClient;
    }

}
