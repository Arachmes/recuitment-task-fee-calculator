<?php

namespace PragmaGoTech\Interview;

use PragmaGoTech\Interview\Exception\InvalidInputException;
use PragmaGoTech\Interview\Model\BreakpointInterface;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\BreakpointProviderInterface;
use PragmaGoTech\Interview\Utils\NumberTransform;
use PragmaGoTech\Interview\Validator\AmountValidator;

class FeeCalculatorService implements FeeCalculator
{

    public function __construct(readonly private BreakpointProviderInterface $breakpointProvider)
    {
    }


    /**
     * @throws InvalidInputException
     */
    public function calculate(LoanProposal $application): float
    {

        $validator = new AmountValidator();
        if (!$validator->isValid($application->amount())) {
            throw new InvalidInputException('Amount is invalid');
        }


        $lowerBreakpoint = $this->breakpointProvider->getLowerBreakpoint($application);
        $higherBreakpoint = $this->breakpointProvider->getHigherBreakpoint($application);

        if (!$lowerBreakpoint || !$higherBreakpoint) {
            throw  new InvalidInputException('Cannot find breakpoint for this application');
        }

        if ($lowerBreakpoint === $higherBreakpoint) {
            return $lowerBreakpoint->getFee();
        }


        $amount = NumberTransform::toInt($application->amount());
        $resultFee = $this->linearInterpolation($amount, $lowerBreakpoint, $higherBreakpoint);


        return NumberTransform::toFloat($this->roundFee($amount, $resultFee));
    }


    private function linearInterpolation(float $amount, BreakpointInterface $lowerBreakpoint, BreakpointInterface $higherBreakpoint): int
    {

        $minAmount = NumberTransform::toInt($lowerBreakpoint->getAmount());
        $maxAmount = NumberTransform::toInt($higherBreakpoint->getAmount());
        $minFee = NumberTransform::toInt($lowerBreakpoint->getFee());
        $maxFee = NumberTransform::toInt($higherBreakpoint->getFee());

        return floor($minFee + ((($maxFee - $minFee) / ($maxAmount - $minAmount)) * ($amount - $minAmount)));

    }

    private function roundFee(int $amount, int $fee): int
    {
        $defaultSum = $amount + $fee;
        $roundedSum = ceil($defaultSum / 500) * 500;
        $diff = $roundedSum - $defaultSum;

        return $fee + $diff;
    }
}