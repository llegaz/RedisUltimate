# 🔴 RedisUltimate — Implementation Roadmap

> PHP library for Redis sets, MQ, and more — ordered by priority.

---

## 1️⃣ Sets — Core Operations

```php
sadd(string $key, mixed ...$members): int      // Add one or more members
srem(string $key, mixed ...$members): int      // Remove one or more members
smembers(string $key): array                   // Return all members
sismember(string $key, mixed $member): bool    // Check if a member exists
scard(string $key): int                        // Count members
```

---

## 2️⃣ Sets — Inter-set Operations

```php
sinter(string ...$keys): array                 // Intersection
sunion(string ...$keys): array                 // Union
sdiff(string $key, string ...$keys): array     // Difference
```

---

## 3️⃣ Key Management

```php
exists(string $key): bool                      // Check existence
del(string ...$keys): int                      // Delete one or more keys
expire(string $key, int $ttl): bool            // Set TTL (seconds)
ttl(string $key): int                          // Get remaining TTL
```

---

## 4️⃣ Hash — Data Storage
will most certainly use [Redis Cache](https://packagist.org/packages/llegaz/redis-cache).

```php
hset(string $key, string $field, mixed $value): int    // Set a field
hget(string $key, string $field): mixed                // Get a field
hgetall(string $key): array                            // Get all fields
hdel(string $key, string ...$fields): int              // Delete one or more fields
```

---

## 5️⃣ Lists — Basic MQ

```php
lpush(string $key, mixed ...$values): int      // Producer: push a message
brpop(string $key, int $timeout): array        // Blocking consumer
rpop(string $key): mixed                       // Non-blocking consumer
llen(string $key): int                         // Queue length
```

### Classic pattern

```
Producer  →  LPUSH queue "message"
Consumer  →  BRPOP queue 0       // blocks until a message arrives
```

---

## 6️⃣ Pub/Sub

```php
publish(string $channel, mixed $message): int          // Publish a message
subscribe(string ...$channels): void                   // Subscribe to channels
unsubscribe(string ...$channels): void                 // Unsubscribe
psubscribe(string ...$patterns): void                  // Pattern matching e.g. "user.*"
```

---

## 7️⃣ Streams — The Final Boss (proper MQ)

```php
// * = auto-generated id
xadd(string $key, array $message, string $id = '*'): string

// Simple read
xread(array $streams, int $count = null, int $block = null): array

// Read via consumer group
xreadgroup(string $group, string $consumer, array $streams, int $count = null): array

// Acknowledge a processed message
xack(string $key, string $group, string ...$ids): int

// Manage consumer groups
xgroup(string $op, string $key, string $group, string $id = '0'): mixed

// Number of messages in the stream
xlen(string $key): int
```


> **Lists** → simple, no ack, fire & forget  
> **Streams** → persistent, ack, consumer groups, replayable ← Kafka-like

---
**@See you space cowboy...** 🚀
