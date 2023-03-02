# Circuit breaker design pattern implementation in PHP

with **default** CircuitBreakerConfig
```php
$circuitBreaker = new CircuitBreaker(new CircuitBreakerConfig());

try {
    $result = $circuitBreaker->execute(function () {
        return 'Success';
    });

    var_dump($result);
} catch (Exception $exception) {

}
```

setup **custom** CircuitBreakerConfig
```php
$circuitBreakerConfig = (new CircuitBreakerConfig())
    ->setHalfOpenTimeout(10)
    ->setThreshold(10);
$circuitBreaker = new CircuitBreaker($circuitBreakerConfig);
```

example for implementing CacheInterface for Redis
```php
class RedisCache implements CacheInterface
{
    public function __construct(private Redis $redis)
    {
        $this->redis = $redis;
    }

    public function get(string $key): mixed
    {
        return unserialize($this->redis->get($key));
    }

    public function set(string $key, mixed $value, int $ttl = 0): void
    {
        $this->redis->setex($key, $ttl, serialize($value));
    }

    public function delete(string $key): void
    {
        $this->redis->del($key);
    }
}
```

[Wikipedia: Circuit breaker design pattern](https://en.wikipedia.org/wiki/Circuit_breaker_design_pattern)