<?php

class CircuitBreakerState
{
    public const STATE_CLOSED = 'CLOSED';
    public const STATE_OPEN = 'OPENED';
    public const STATE_HALF_OPEN = 'HALF-OPEN';

    private string $state;

    public function __construct(string $state)
    {
        if (!in_array($state, CircuitBreakerStates::STATES)) {
            throw new InvalidArgumentException("Invalid circuit breaker state: $state");
        }

        $this->state = $state;
    }

    public function getState(): string
    {
        return $this->state;
    }
}