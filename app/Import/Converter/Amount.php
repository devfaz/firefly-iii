<?php
/**
 * Amount.php
 * Copyright (C) 2016 thegrumpydictator@gmail.com
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

declare(strict_types = 1);

namespace FireflyIII\Import\Converter;

/**
 * Class RabobankDebetCredit
 *
 * @package FireflyIII\Import\Converter
 */
class Amount extends BasicConverter implements ConverterInterface
{

    /**
     * Some people, when confronted with a problem, think "I know, I'll use regular expressions." Now they have two problems.
     * - Jamie Zawinski
     *
     * 0.01 4 1
     * 0.10 4 1
     * 0.1  3 0
     * 1.00 4 1
     * 1.0  3 0
     * 1    1 -3
     * 1.01 4 1
     * 1.10 4 1
     * 1.1  3 0
     * 1.11 4 1
     *
     *
     * @param $value
     *
     * @return float
     */
    public function convert($value): float
    {
        $len             = strlen($value);
        $decimalPosition = $len - 3;
        $decimal         = null;

        if (($len > 2 && $value{$decimalPosition} == '.') || ($len > 2 && strpos($value, '.') > $decimalPosition)) {
            $decimal = '.';
        } else if ($len > 2 && $value{$decimalPosition} == ',') {
            $decimal = ',';
        } 

        // if decimal is dot, replace all comma's and spaces with nothing. then parse as float (round to 4 pos)
        if ($decimal === '.') {
            $search = [',', ' '];
            $value  = str_replace($search, '', $value);
        }
        if ($decimal === ',') {
            $search = ['.', ' '];
            $value  = str_replace($search, '', $value);
            $value  = str_replace(',', '.', $value);
        }
        if (is_null($decimal)) {
            // replace all:
            $search = ['.', ' ', ','];
            $value  = str_replace($search, '', $value);
        }

        $this->setCertainty(90);


        return round(floatval($value), 4);

    }
}
