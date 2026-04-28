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
class FunctionalTest extends \PHPUnit\Framework\TestCase
{
    protected $ultimate;
    protected array $set1 = ['apple', 'pear', 'banana', 'carrot'];
    protected array $set2 = ['apple', 'banana', 'kiwi'];
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

        $this->ultimate->add($this->setsName[0], ...$this->set1);
        $this->ultimate->add($this->setsName[1], ...$this->set2);
        $this->ultimate->add($this->setsName[2], ...$this->set3);

        $this->assertEqualsCanonicalizing(
            ['banana', 'apple'],
            $this->ultimate->intersect(
                $this->setsName[0],
                $this->setsName[1],
                $this->setsName[2],
            )
        );
        $this->assertEquals(
            ['carrot'],
            $this->ultimate->difference(
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
        $this->assertFalse($this->ultimate->isMember($this->setsName[2], 'truc'));
        $this->assertTrue($this->ultimate->isMember($this->setsName[2], 'apple'));
        $this->assertSame(
            1,
            $this->ultimate->remove($this->setsName[2], 'apple')
        );
        $this->assertEquals(
            ['banana'],
            $this->ultimate->intersect(
                $this->setsName[0],
                $this->setsName[1],
                $this->setsName[2],
            )
        );

        $this->assertFalse($this->ultimate->isMember($this->setsName[0], 'truc'));
        $this->assertTrue($this->ultimate->isMember($this->setsName[0], 'carrot'));
        $this->assertSame(
            1,
            $this->ultimate->remove($this->setsName[0], 'carrot')
        );
        $diff =             $this->ultimate->difference(
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
            3,
            $this->ultimate->remove(
                $this->setsName[1],
                'apple',
                'banana',
                'kiwi',
                'truc'
            )
        );
        $this->assertSame(0, $this->ultimate->count($this->setsName[1]));
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
