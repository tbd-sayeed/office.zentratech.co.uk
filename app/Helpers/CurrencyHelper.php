<?php

namespace App\Helpers;

class CurrencyHelper
{
    public const SUPPORTED = [
        'GBP' => ['symbol' => '£', 'name' => 'British Pound'],
        'USD' => ['symbol' => '$', 'name' => 'US Dollar'],
        'EUR' => ['symbol' => '€', 'name' => 'Euro'],
        'BDT' => ['symbol' => '৳', 'name' => 'Bangladeshi Taka'],
    ];

    public static function symbol(string $code): string
    {
        return self::SUPPORTED[$code]['symbol'] ?? $code . ' ';
    }

    public static function format(float $amount, string $code = 'GBP'): string
    {
        return (self::SUPPORTED[$code]['symbol'] ?? $code . ' ') . number_format($amount, 2);
    }

    public static function all(): array
    {
        return self::SUPPORTED;
    }

    /** Convert amount to GBP for aggregation. Approximate rates. */
    public static function toBase(float $amount, string $fromCurrency, string $base = 'GBP'): float
    {
        $ratesToGbp = ['GBP' => 1, 'USD' => 0.79, 'EUR' => 0.85, 'BDT' => 0.0075];
        return $amount * ($ratesToGbp[$fromCurrency] ?? 1);
    }

    /** Convert amount from GBP to target currency. */
    public static function fromBase(float $amountGbp, string $toCurrency): float
    {
        $ratesToGbp = ['GBP' => 1, 'USD' => 0.79, 'EUR' => 0.85, 'BDT' => 0.0075];
        $rate = $ratesToGbp[$toCurrency] ?? 1;
        return $rate > 0 ? $amountGbp / $rate : 0;
    }
}
