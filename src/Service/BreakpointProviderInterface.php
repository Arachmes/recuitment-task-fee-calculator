<?php

namespace PragmaGoTech\Interview\Service;

use PragmaGoTech\Interview\Model\BreakpointInterface;
use PragmaGoTech\Interview\Model\LoanProposalInterface;

interface BreakpointProviderInterface
{
    public function getLowerBreakpoint(LoanProposalInterface $loanProposal): ?BreakpointInterface;

    public function getHigherBreakpoint(LoanProposalInterface $loanProposal): ?BreakpointInterface;
}