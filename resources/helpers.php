<?php

if(! function_exists('is_money')) {
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
    function is_money($number) {
        return preg_match("/^[0-9]{1,3}(,?[0-9]{3})*(\.[0-9]{1,2})?$/", $number) ||
            preg_match("/^[0-9]{1,3}(\.?[0-9]{3})*(,[0-9]{1,2})?$/", $number);
    }
}
