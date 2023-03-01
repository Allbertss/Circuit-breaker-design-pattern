# Circuit breaker design pattern implementation in PHP

with **default** CircuitBreakerConfig
```php
$circuitBreakerConfig = new CircuitBreakerConfig();
$circuitBreaker = new CircuitBreaker($circuitBreakerConfig);

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

[Wikipedia: Circuit breaker design pattern](https://en.wikipedia.org/wiki/Circuit_breaker_design_pattern)