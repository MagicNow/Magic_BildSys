<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

if (! function_exists('is_money')) {
    /**
     * Validate money in US and other patterns without the prefix or sufix.
     * Only validates numbers with commas and dots.
     * Ex: 100,00  // is valid
     * Ex: 100.00  // is valid
     * Ex: 100a00  // is invalid
     * Ex: 1,000.0 // is valid
     * Ex: 1.000,0 // is valid
     * @param string $number
     *
     * @return bool
     */
    function is_money($number)
    {
        return preg_match("/^[0-9]{1,3}(,?[0-9]{3})*(\.[0-9]{1,2})?$/", $number) ||
            preg_match("/^[0-9]{1,3}(\.?[0-9]{3})*(,[0-9]{1,2})?$/", $number);
    }

}

if(! function_exists('money_to_float')) {
    /**
     * Transforms a valid money string to float
     *
     * @param string $number
     *
     * @return float
     */
    function money_to_float ($number) {
        if (preg_match("/^(-)?[0-9]{1,3}(,?[0-9]{3})*(\.[0-9]{1,2})?$/", $number)) {
            return (float) str_replace(',', '', $number);
        } elseif(preg_match("/^(-)?[0-9]{1,3}(\.?[0-9]{3})*(,[0-9]{1,2})?$/", $number)) {
            return (float) str_replace(',', '.', str_replace('.', '', $number));
        } elseif(is_null($number)) {
            return (float) 0;
        } else {
            throw new InvalidArgumentException(
                'The parameter is not a valid money string. Ex.: 100.00, 100,00, 1.000,00, 1,000.00'
            );
        }
    }
}

if(! function_exists('float_to_money')) {
    /**
     * Transforms a float to a currency formatted string
     *
     * @param float $number
     *
     * @return string
     */
    function float_to_money($number, $prefix = 'R$ ')
    {
        return $prefix . number_format($number, 2, ',', '.');
    }
}

Collection::macro('dd', function () {
    dd($this);
});

EloquentCollection::macro('dd', function () {
    dd($this);
});
