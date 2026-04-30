<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Tests\Functional;

use LLegaz\Ultimate\RedisUltimate as SUT;
use LLegaz\Ultimate\Tests\TestState;

/**
 * test some functional scenarios with sorted sets
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
    protected array $set4 = [
        1777492552 => 'msg:123',
        1777492662 => 'msg:234',
        1777482552 => 'msg:456',
        1888492552 => 'msg:789',
    ];

    protected array $expected1 = [
        0 => "apple",
        1 => "banana",
        2 => "carrot",
        3 => "pear",
    ];
    protected array $expected2 = [
        0 => "kiwi",
        1 => "banana",
        2 => "apple",
    ];
    protected array $expected3 = [
        0 => "apple",
        1 => "pear",
        2 => "banana",
    ];
    protected array $expected4 = [
        0 => "msg:456",
        1 => "msg:123",
        2 => "msg:234",
        3 => "msg:789",
    ];
    protected array $setsName = ['ztest1', 'ztest2', 'ztest3', 'ztest4'];

    protected function setUp(): void {
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
        foreach ($this->setsName as $name ) {
            switch ($name) {
                case 'ztest1': $data = $this->set1;
                    $expected = $this->expected1; break;
                case 'ztest2': $data = $this->set2;
                    $expected = $this->expected2; break;
                case 'ztest3': $data = $this->set3;
                    $expected = $this->expected3; break;
                case 'ztest4': $data = $this->set4;
                    $expected = $this->expected4; break;
                default : $data=[];
            }
            if ($this->ultimate->zcard($name)) {
                $this->ultimate->deleteSet($name);
            }
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $this->ultimate->zadd(
                        $name,
                        array_key_first($value),
                        array_values($value)[0]
                    ); // yeah that's ugly
                } else {
                    $this->ultimate->zadd($name, $key, $value);
                }
            }
            $result = $this->ultimate->zrange($name);
            $this->assertSame($expected, $result);
        }
    }

    /**
     *
     * @depends testInitSets
     */
    public function testRemoveFromZSet(): void
    {
        $this->ultimate->zupdate($this->setsName[3], 1777482552, 'updated');
        $this->expected4[0] = 'updated';
        $this->assertSame($this->expected4, $this->ultimate->zrange($this->setsName[3]));

        $this->ultimate->zrem($this->setsName[2], 'banana');
        $this->ultimate->zrem($this->setsName[2], 'apple');
        $this->ultimate->zrem($this->setsName[2], 'pear');
        $this->assertSame([], $this->ultimate->zrange($this->setsName[2]));
    }

    /**
     *
     * @depends testRemoveFromZSet
     */
    public function testIntersections(): void
    {
        $res1 = $this->ultimate->zinter($this->setsName[0], $this->setsName[1]);
        $res2 = $this->ultimate->zrevinter($this->setsName[1], $this->setsName[0]);
        $this->assertNotSame($res2, $res1);
        $this->assertSame($res1[0], $res2[1]);
        $this->assertSame($res1[1], $res2[0]);
        $e = 'banana'; // lowest score by arrays indexes
        $this->assertSame($e, $res1[0]);
        $this->assertSame($e, $res2[1]);
        $e = 'apple'; // highiest score by arrays indexes
        $this->assertSame($e, $res1[1]);
        $this->assertSame($e, $res2[0]);
    }

    /**
     *
     * @depends testIntersections
     */
    public function testCleanSets(): void
    {
        foreach ($this->setsName as $name ) {
            $this->assertTrue($this->ultimate->deleteSet($name));
            $this->assertSame([], $this->ultimate->zrange($name));
        }
    }
}
