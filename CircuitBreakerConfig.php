<?php

class CircuitBreakerConfig
{
    private int $threshold = 5;
    private ?int $halfOpenTimeout = 10;

    public function getThreshold(): int
    {
        return $this->threshold;
    }

    public function setThreshold(int $threshold): self
    {
        $this->threshold = $threshold;

        return $this;
    }

    public function getHalfOpenTimeout(): int
    {
        return $this->halfOpenTimeout;
    }

    public function setHalfOpenTimeout(int $halfOpenTimeout): self
    {
        $this->halfOpenTimeout = $halfOpenTimeout;

        return $this;
    }
}