<?php

/**
 * Transliterator
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Localization;

class Transliterator
{

    public static function slugify($string)
    {
        $prepared       = str_replace(
                array('я', 'ю', 'ї', 'є', 'ж', 'ч', 'ш', 'щ', 'ь'), array('ya', 'yu', 'yi', 'ye', 'zh', 'ch', 'sh', 'sch', ''), $string);
        $transliterated = \Transliterator::create('Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();')->transliterate($prepared);

        $clean = preg_replace('/\W/i', '-', $transliterated);

        $replaced = str_replace('--', '-', $clean);
        $result   = preg_replace('/[[:^print:]]/', '', $replaced);

        return $result;

    }

}
