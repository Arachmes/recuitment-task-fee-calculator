<?php

namespace PragmaGoTech\Interview\Repository;


use PragmaGoTech\Interview\Exception\BadSourceException;
use PragmaGoTech\Interview\Model\Breakpoint;
use PragmaGoTech\Interview\Model\BreakpointInterface;
use PragmaGoTech\Interview\Model\LoanProposalInterface;
use PragmaGoTech\Interview\Service\BreakpointProviderInterface;
use PragmaGoTech\Interview\Utils\NumberTransform;

class BreakpointRepository implements BreakpointProviderInterface
{

    private string $dataSourceFile = __DIR__ . '/../../config/breakpoints.php';

    /**
     * @var array
     */
    private array $breakpoints;


    /**
     * @throws BadSourceException
     */
    public function __construct()
    {
        if (!is_file($this->dataSourceFile)) {
            throw new BadSourceException('Source file not exist');
        }

        $data = include($this->dataSourceFile);
        $parsedData = [];

        foreach ($data as $term => $breakpointsArray) {
            foreach ($breakpointsArray as $breakpointData) {

                if (!isset($breakpointData['amount']) || !isset($breakpointData['fee'])) {
                    throw new BadSourceException('Bad source file structure');
                }

                $parsedData[$term][NumberTransform::toInt($breakpointData['amount'])] = new Breakpoint($breakpointData['amount'], $breakpointData['fee']);
            }
        }

        $this->breakpoints = $parsedData;
    }

    public function getLowerBreakpoint(LoanProposalInterface $loanProposal): ?BreakpointInterface
    {

        if(!isset($this->breakpoints[$loanProposal->term()])){
            return null;
        }

        $breakpoints = $this->breakpoints[$loanProposal->term()];
        ksort($breakpoints);


        $lastKey = null;
        /** @var BreakpointInterface $breakpoint */
        foreach ($breakpoints as $key => $breakpoint) {
            if ($breakpoint->getAmount() == $loanProposal->amount()) {
                return $breakpoint;
            } else if ($breakpoint->getAmount() > $loanProposal->amount()) {
                return $breakpoints[$lastKey];
            }

            $lastKey = $key;
        }

        return null;
    }

    public function getHigherBreakpoint(LoanProposalInterface $loanProposal): ?BreakpointInterface
    {
        if(!isset($this->breakpoints[$loanProposal->term()])){
            return null;
        }

        $breakpoints = $this->breakpoints[$loanProposal->term()];
        krsort($breakpoints);

        $lastKey = null;
        /** @var BreakpointInterface $breakpoint */
        foreach ($breakpoints as $key => $breakpoint) {
            if ($breakpoint->getAmount() == $loanProposal->amount()) {
                return $breakpoint;
            } else if ($breakpoint->getAmount() < $loanProposal->amount()) {
                return $breakpoints[$lastKey];
            }

            $lastKey = $key;
        }

        return null;
    }
}