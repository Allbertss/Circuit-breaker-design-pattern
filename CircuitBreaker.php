<?php

class CircuitBreaker
{
    public function __construct(
        private readonly CircuitBreakerConfig $config,
        private readonly CacheInterface       $cache,
    )
    {
        $this->cache->set('halfOpenTimeout', $this->config->getHalfOpenTimeout());
        $this->cache->set('state', CircuitBreakerState::STATE_CLOSED);
    }

    /**
     * @throws CircuitBreakerStateOpenException
     */
    public function execute(callable $function): mixed
    {
        if ($this->cache->get('state') === CircuitBreakerState::STATE_OPEN) {
            if (time() > $this->cache->get('halfOpenTimeout')) {
                $this->cache->set('state', CircuitBreakerState::STATE_HALF_OPEN, $this->config->getHalfOpenTimeout());
                $this->cache->set('failures', 0);
            } else {
                throw new CircuitBreakerStateOpenException();
            }
        }

        try {
            $result = $function();

            $this->reset();

            return $result;
        } catch (Exception $exception) {
            $this->cache->set('failures', $this->cache->get('failures') + 1);

            if ($this->cache->get('failures') >= $this->config->getThreshold()) {
                $this->cache->set('state', CircuitBreakerState::STATE_OPEN);
                $this->cache->set('halfOpenTimeout', time() + $this->config->getHalfOpenTimeout());

                throw new CircuitBreakerStateOpenException();
            }

            throw $exception;
        }
    }

    private function reset(): void
    {
        $this->cache->set('failures', 0);
        $this->cache->set('halfOpenTimeout', $this->config->getHalfOpenTimeout());
        $this->cache->set('state', CircuitBreakerState::STATE_CLOSED);
    }
}