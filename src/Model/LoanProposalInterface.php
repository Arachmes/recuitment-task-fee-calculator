<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

interface LoanProposalInterface
{
    public function term(): int;

    public function amount(): float;
}