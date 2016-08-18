<?php

/**
 * Announce
 * @copyright Copyright (c) 2011 - 2013 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Helper;

class Announce
{

    public function getString($incomeString, $num = 300)
    {
        $stringStriped = strip_tags($incomeString);
        if (!$stringStriped) {
            return;
        }

        $textBr = str_replace(array("\r\n", "\r", "\n"), "<br>", $stringStriped);
        $string = mb_substr(strip_tags($textBr), 0, 300, 'utf-8');

        if (mb_strlen($string, 'utf-8') < $num) {
            return $string;
        }

        $subString = mb_substr($string, 0, $num, 'utf-8');
        $array     = explode(' ', $subString);

        $array[count($array) - 1] = '...';
        $output                   = implode(' ', $array);

        return $output;

    }

}
