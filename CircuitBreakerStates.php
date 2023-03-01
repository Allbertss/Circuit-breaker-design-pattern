<?php

class CircuitBreakerStates
{
    public const STATES = [
        CircuitBreakerState::STATE_CLOSED,
        CircuitBreakerState::STATE_OPEN,
        CircuitBreakerState::STATE_HALF_OPEN,
    ];
}