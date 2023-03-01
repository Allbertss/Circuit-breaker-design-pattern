<?php

class CircuitBreaker
{
    private int $failures = 0;
    private int $halfOpenTimeout = 0;
    private CircuitBreakerState $state;
    private CircuitBreakerConfig $config;

    public function __construct(CircuitBreakerConfig $config)
    {
        $this->config = $config;

        $this->halfOpenTimeout = $this->config->getHalfOpenTimeout();
        $this->state = new CircuitBreakerState(CircuitBreakerState::STATE_CLOSED);
    }

    /**
     * @throws Exception
     */
    public function execute(callable $function): mixed
    {
        if ($this->state->getState() === CircuitBreakerState::STATE_OPEN) {
            if (time() > $this->halfOpenTimeout) {
                $this->state = new CircuitBreakerState(CircuitBreakerState::STATE_HALF_OPEN);
                $this->failures = 0;
            } else {
                throw new Exception("Circuit breaker is {$this->state->getState()}");
            }
        }

        try {
            $result = $function();

            $this->reset();

            return $result;
        } catch (Exception $exception) {
            $this->failures++;

            if ($this->failures >= $this->config->getThreshold()) {
                $this->state = new CircuitBreakerState(CircuitBreakerState::STATE_OPEN);
                $this->halfOpenTimeout = time() + $this->config->getHalfOpenTimeout();

                throw new Exception("Circuit breaker is {$this->state->getState()}");
            }

            throw $exception;
        }
    }

    private function reset(): void
    {
        $this->failures = 0;
        $this->halfOpenTimeout = $this->config->getHalfOpenTimeout();
        $this->state = new CircuitBreakerState(CircuitBreakerState::STATE_CLOSED);
    }
}