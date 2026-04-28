<?php

declare(strict_types=1);

namespace LLegaz\Ultimate\Set;

/**
 * move this in LLegaz\Redis\RedisClientInterface
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

    /**
     * Compute the intersection of one or more sets and return the cardinality of the result.
     * 
     * @param <string>array $keys One or more set key names.
     * @param int $limit A maximum cardinality to return. This is useful to put an upper bound on the amount of work Redis will do.
     * @return array|false 
     */
    public function sinter(array $keys, int $limit = -1): array | false;
}
