<?php

declare(strict_types=1);

namespace LLegaz\Ultimate;

use LLegaz\Redis\RedisAdapter;
use LLegaz\Ultimate\Exception\InvalidArgumentException;
use LLegaz\Ultimate\Exception\InvalidKeyException;

/**
 * This class purpose is mainly to handle SETs operations but also to wrap other utility methods not handled in RedisCache
 *
 *
 *
 * <b>Convention</b>
 * KEY is the set's NAME
 * MEMBER(s) the set's members
 *
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
class RedisUltimate extends RedisAdapter
{
    public function deleteSet(string $key): bool
    {
        if (!$this->isConnected()) {
            $this->throwCLEx();
        }

        try {
            $redisResponse = $this->getRedis()->del($key);
        } catch (\Throwable $t) {
            $redisResponse = null;
            $this->formatException($t);
        } finally {
            return ($redisResponse >= 0) ? true : false;
        }
    }

    /**
     * Remove one or more members
     *
     * @param string $key
     * @param mixed $members
     * @return int
     */
    public function remove(string $key, mixed ...$members): int
    {
        $redisResponse = 0;
        if (!count($members)) {
            throw new InvalidArgumentException();
        }
        $this->init($key);

        try {
            $redisResponse = call_user_func_array([$this->getRedis(), 'srem'], array_merge([$key], $members));
        } catch (\Throwable $t) {
            $redisResponse = 0;
            dump('fail', $t);
            $this->formatException($t);
        } finally {
            return $redisResponse;
        }
    }

    /**
     * Add one or more members
     *
     * @param string $key
     * @param mixed $members
     * @return int
     */
    public function add(string $key, mixed ...$members): int
    {
        $redisResponse = 0;
        if (!count($members)) {
            throw new InvalidArgumentException();
        }
        $this->init($key);

        try {
            $redisResponse = call_user_func_array([$this->getRedis(), 'sadd'], array_merge([$key], $members));
        } catch (\Throwable $t) {
            $redisResponse = 0;
            $this->formatException($t);
        } finally {
            return $redisResponse;
        }
    }

    /**
     * Count members
     *
     * @param string $key
     * @return int
     */
    public function count(string $key): int
    {
        $redisResponse = 0;
        $this->init($key);

        try {
            $redisResponse = $this->getRedis()->scard($key);
        } catch (\Throwable $t) {
            $redisResponse = [];
            $this->formatException($t);
        } finally {
            return $redisResponse;
        }
    }


    /**
     * Return all members
     *
     * @param string $key
     * @return array the set members
     */
    public function members(string $key): array
    {
        $redisResponse = [];
        $this->init($key);

        try {
            $redisResponse = $this->getRedis()->smembers($key);
        } catch (\Throwable $t) {
            $redisResponse = [];
            $this->formatException($t);
        } finally {
            return $redisResponse;
        }
    }

    /**
     * Check if a member exists
     *
     * @param string $key
     * @param mixed $member
     * @return bool
     */
    public function isMember(string $key, mixed $member): bool
    {
        $redisResponse = false;
        if (!$member) {
            throw new InvalidArgumentException();
        }
        $this->init($key);

        try {
            $redisResponse = $this->getRedis()->sismember($key, $member);
        } catch (\Throwable $t) {
            $redisResponse = false;
            $this->formatException($t);
        } finally {
            return $redisResponse;
        }
    }

    /**
     *  Compute the intersection of one or more sets and return intersected members of all SETs.
     *
     * @param <string>array $keys One or more set key names.
     * @return array|false
     */
    public function intersect(string ...$keys): array | false
    {
        $redisResponse = false;
        if (!$this->isConnected()) {
            $this->throwCLEx();
        }

        try {
            if ($keys) {
                $redisResponse = $this->getRedis()->sinter($keys);
            }
        } catch (\Throwable $t) {
            $redisResponse = false;
            $this->formatException($t);
        } finally {
            return $redisResponse;
        }
    }

    /**
     * Given one or more Redis SETS, this command returns all of the members from the first set that are not in any subsequent set.
     *
     * @param string $keys
     * @return array|false
     */
    public function difference(string ...$keys): array | false
    {
        $redisResponse = false;
        if (!$this->isConnected()) {
            $this->throwCLEx();
        }

        try {
            if ($keys) {
                $redisResponse = $this->getRedis()->sdiff($keys);
            }
        } catch (\Throwable $t) {
            $redisResponse = false;
            $this->formatException($t);
        } finally {
            return $redisResponse;
        }
    }
    /**************************************************
     * Non SET(s) operations
     *
     */

    /**
     * increment a counter stored in Redis
     *
     * @param string $key
     * @return int
     */
    public function incrementCounter(string $key): int
    {
        $this->init($key);

        return $this->getRedis()->incr($key);
    }

    /**
     *
     * @param string $key
     * @return void
     * @throws InvalidKeyException
     * @throws \LLegaz\Redis\Exception\ConnectionLostException
     */
    private function init(string $key): void
    {
        if (!strlen($key)) {
            throw new InvalidKeyException();
        }
        if (!$this->isConnected()) {
            $this->throwCLEx();
        }
    }
}
