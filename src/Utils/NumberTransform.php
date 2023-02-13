<?php

namespace PragmaGoTech\Interview\Utils;

class NumberTransform
{
    public static function toInt(float $value): int
    {
        return (int)($value * 100);
    }

    public static function toFloat(int $value): float
    {
        return round($value / 100, 2);
    }
}