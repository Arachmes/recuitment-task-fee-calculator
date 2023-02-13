<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

interface BreakpointInterface
{
    public function getAmount(): float;

    public function getFee(): float;
}