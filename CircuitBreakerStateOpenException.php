<?php

class CircuitBreakerStateOpenException extends CircuitBreakerException
{
    public function __construct()
    {
        parent::__construct('Circuit breaker is ' . CircuitBreakerState::STATE_OPEN);
    }
}