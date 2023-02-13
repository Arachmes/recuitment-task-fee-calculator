<?php

namespace PragmaGoTech\Interview\Validator;

class AmountValidator implements ValidatorInterface
{

    const MIN_AMOUNT = 1000;
    const MAX_AMOUNT = 20000;

    public function isValid(mixed $value): bool
    {

        if (!is_numeric($value) || $value < self::MIN_AMOUNT || $value > self::MAX_AMOUNT) {
            return false;
        }

        return true;
    }
}