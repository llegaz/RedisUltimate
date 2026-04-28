<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Set;

/**
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
interface RedisSetOperationsInterface {

    /**
     * Add one or more members
     * 
     * @param string $key
     * @param mixed $members
     * @return int
     */
    public function sadd(string $key, mixed ...$members): int;

    /**
     * Remove one or more members
     * 
     * @param string $key
     * @param mixed $members
     * @return int
     */
    public function srem(string $key, mixed ...$members): int;

    /**
     * Return all members
     * 
     * @param string $key
     * @return array the set members
     */
    public function smembers(string $key): array;

    /**
     * Check if a member exists
     * 
     * @param string $key
     * @param mixed $member
     * @return bool
     */
    public function sismember(string $key, mixed $member): bool;

    /**
     * Count members
     * 
     * @param string $key
     * @return int
     */
    public function scard(string $key): int;
}
