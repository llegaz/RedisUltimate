<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Tests\Functional;

use LLegaz\Ultimate\RedisUltimate as SUT;
use LLegaz\Ultimate\Tests\TestState;

/**
 * test some functional scenarios
 *
 * (local redis should be up and running)
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
class FunctionalZTest extends \PHPUnit\Framework\TestCase
{
    protected $ultimate;
    protected array $set1 = [[1 => 'apple'],[1 => 'pear'],[1 => 'banana'],[1 => 'carrot']];
    protected array $set2 = ['kiwi', 'banana', 'apple'];
    protected array $set3 = ['apple', 'pear', 'banana'];
    protected array $setsName = ['test1', 'test2', 'test3'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->ultimate = new SUT();

        if (!TestState::$adapterClassDisplayed) {
            TestState::$adapterClassDisplayed = true;
            fwrite(STDERR, PHP_EOL);
            dump($this->ultimate->getRedis()->toString() . ' adapter used.');
        }
    }

    /**
     *
     * @return void
     */
    public function testInitSets(): void
    {

        $this->ultimate->zadd($this->setsName[0], ...$this->set1);
        $this->ultimate->zadd($this->setsName[1], ...$this->set2);
        $this->ultimate->zadd($this->setsName[2], ...$this->set3);

        $this->assertSame(
            ['apple', 'banana'],
            $this->ultimate->zintersect(
                $this->setsName[0],
                $this->setsName[1],
                $this->setsName[2],
            )
        );
        $this->assertEquals(
            ['carrot'],
            $this->ultimate->zdifference(
                $this->setsName[0],
                $this->setsName[1],
                $this->setsName[2],
            )
        );
    }

    /**
     *
     * @depends testInitSets
     */
    public function testRemoveFromSet(): void
    {
        $this->assertFalse($this->ultimate->zisMember($this->setsName[2], 'truc'));
        $this->assertTrue($this->ultimate->zisMember($this->setsName[2], 'apple'));
        $this->assertSame(
            1,
            $this->ultimate->zremove($this->setsName[2], 'apple')
        );
        $this->assertEquals(
            ['banana'],
            $this->ultimate->zintersect(
                $this->setsName[0],
                $this->setsName[1],
                $this->setsName[2],
            )
        );

        $this->assertFalse($this->ultimate->zisMember($this->setsName[0], 'truc'));
        $this->assertTrue($this->ultimate->zisMember($this->setsName[0], 'carrot'));
        $this->assertSame(
            1,
            $this->ultimate->zremove($this->setsName[0], 'carrot')
        );
        $diff = $this->ultimate->zdifference(
            $this->setsName[0],
            $this->setsName[1],
            $this->setsName[2],
        );
        $this->assertEquals([], $diff);
        $this->assertSame(0, count($diff));
    }


    /**
     *
     * @depends testRemoveFromSet
     */
    public function testCountSet(): void
    {
        $this->assertSame(3, $this->ultimate->count($this->setsName[1]));
        $this->assertSame(
            ['apple',
                'banana',
                'kiwi',],
            $this->ultimate->zmembers(
                $this->setsName[1]
                    )
        );
        $this->assertSame(
            3,
            $this->ultimate->zremove(
                $this->setsName[1],
                'apple',
                'banana',
                'kiwi',
                'truc'
            )
        );
        $this->assertSame(0, $this->ultimate->zcount($this->setsName[1]));
    }

    /**
     *
     * @depends testCountSet
     */
    public function testCleanSets(): void
    {
        $this->assertTrue($this->ultimate->deleteSet($this->setsName[0]));
        $this->assertTrue($this->ultimate->deleteSet($this->setsName[1]));
        $this->assertTrue($this->ultimate->deleteSet($this->setsName[2]));
    }
}
