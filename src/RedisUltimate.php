<?php

declare(strict_types=1);

namespace LLegaz\Ultimate;

use LLegaz\Redis\RedisAdapter;

/**
 * This class purpose is mainly to handle SETs operations but also to wrap other utility methods not handled in RedisCache
 * 
 * 
 * <b>Convention</b>
 * KEY is the set's NAME
 * MEMBER(s) the set's members
 * 
 * @author Laurent LEGAZ <laurent@legaz.eu>
 */
class RedisUltimate extends RedisAdapter {
    const MAX_RESULTS = 256;

    public function deleteSet(string $key): bool {
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
    public function remove(string $key, array $members): int {
        // use srem
    }

    /**
     * Add one or more members
     * 
     * @param string $key
     * @param mixed $members
     * @return int
     */
    public function add(string $key, array $members): int {
        // use sadd
    }

    /**
     * Count members
     * 
     * @param string $key
     * @return int
     */
    public function count(string $key): int{
        // use scard
    }


    /**
     * Return all members
     * 
     * @param string $key
     * @return array the set members
     */
    public function members(string $key): array {
        
    }// use smembers

    /**
     * Check if a member exists
     * 
     * @param string $key
     * @param mixed $member
     * @return bool
     */
    public function isMember(string $key, mixed $member): bool {
        // use sismember
    }

    /**
     *  Compute the intersection of one or more sets and return MAX_RESULTS intersected members of SETs.
     * 
     * @param <string>array $keys One or more set key names.
     * @return array|false 
     */
    public function intersect(array $keys): array | false {
        $redisResponse = false;
        if (!$this->isConnected()) {
            $this->throwCLEx();
        }

        try {
            if (count($keys)) {
                $redisResponse = $this->getRedis()->sinter($keys, self::MAX_RESULTS);
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
    public function incrementCounter(string $key): int {
        return $this->getRedis()->incr($key);
    }

}
