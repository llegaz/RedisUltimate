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
    protected array $set4 = [
        1777492552 => 'msg:123',
        1777492662 => 'msg:234',
        1777482552 => 'msg:456',
        1888492552 => 'msg:789',
    ];
    protected array $setsName = ['ztest1', 'ztest2', 'ztest3', 'ztest4'];

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
        foreach ($this->setsName as $name ) {
            switch ($name) {
                case 'ztest1': $data = $this->set1;break;
                case 'ztest2': $data = $this->set2;break;
                case 'ztest3': $data = $this->set3;break;
                case 'ztest4': $data = $this->set4;break;
                default : $data=[];
            }
            foreach ($data as $key => $value) {
                dump($key, $value);
            }
        }
        $this->assertTrue(true);
    }

    /**
     *
     * @depends testInitSets
     */
    public function testRemoveFromSet(): void
    {
        $this->assertFalse(false);
    }

    /**
     *
     * @depends testRemoveFromSet
     */
    public function testCleanSets(): void
    {
        $this->assertTrue($this->ultimate->deleteSet($this->setsName[0]));
        $this->assertTrue($this->ultimate->deleteSet($this->setsName[1]));
        $this->assertTrue($this->ultimate->deleteSet($this->setsName[2]));
        $this->assertTrue($this->ultimate->deleteSet($this->setsName[3]));
    }
}
