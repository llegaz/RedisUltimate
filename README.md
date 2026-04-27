# RedisUltimate

todo :

- secure repo and workflow with dev branch


## Sets de base
sadd(string $key, mixed ...$members): int
srem(string $key, mixed ...$members): int
smembers(string $key): array
sismember(string $key, mixed $member): bool
scard(string $key): int  // count
## Opérations entre sets
sinter(string ...$keys): array      // intersection
sunion(string ...$keys): array      // union
sdiff(string $key, string ...$keys): array  // différence
## Gestion des clés
exists(string $key): bool
del(string ...$keys): int
expire(string $key, int $ttl): bool
ttl(string $key): int
## Hash (pour stocker les données liées à tes sets)
hset(string $key, string $field, mixed $value): int
hget(string $key, string $field): mixed
hgetall(string $key): array
hdel(string $key, string ...$fields): int







## Lists pour du MQ basique :
lpush(string $key, mixed ...$values): int  // producer
brpop(string $key, int $timeout): array    // consumer bloquant
rpop(string $key): mixed                   // consumer non bloquant
llen(string $key): int
## Pattern classique :
Producer → LPUSH queue "message"
Consumer → BRPOP queue 0  // bloque jusqu'à un message



## Pub/Sub basique
publish(string $channel, mixed $message): int
subscribe(string ...$channels): void
unsubscribe(string ...$channels): void
psubscribe(string ...$patterns): void  // pattern matching ex: "user.*"

## Streams (MQ proper) - le boss de fin
xadd(string $key, array $message, string $id = '*'): string  // * = auto id
xread(array $streams, int $count = null, int $block = null): array
xreadgroup(string $group, string $consumer, array $streams, int $count = null): array
xack(string $key, string $group, string ...$ids): int
xgroup(string $op, string $key, string $group, string $id = '0'): mixed
xlen(string $key): int