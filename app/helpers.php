<?php

use App\Helpers\CurrencyHelper;

if (!function_exists('currency_format')) {
    function currency_format(float $amount, ?string $code = 'GBP'): string
    {
        return CurrencyHelper::format($amount, $code ?? 'GBP');
    }
}

if (!function_exists('format_amounts_by_currency')) {
    /** @param array<int, array{amount: float, currency: string}> $items */
    function format_amounts_by_currency(iterable $items): string
    {
        $byCur = [];
        foreach ($items as $item) {
            $c = $item['currency'] ?? 'GBP';
            $byCur[$c] = ($byCur[$c] ?? 0) + (float) ($item['amount'] ?? 0);
        }
        if (empty($byCur)) return currency_format(0, 'GBP');
        return implode(' + ', array_map(fn($amt, $code) => currency_format($amt, $code), array_values($byCur), array_keys($byCur)));
    }
}
