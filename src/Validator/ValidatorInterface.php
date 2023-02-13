<?php

namespace PragmaGoTech\Interview\Validator;

interface ValidatorInterface
{
    public function isValid(mixed $value): bool;
}