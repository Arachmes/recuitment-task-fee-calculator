<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

class Breakpoint implements BreakpointInterface
{


    public function __construct(
        readonly private float $amount,
        readonly private float $fee)
    {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getFee(): float
    {
        return $this->fee;
    }
}