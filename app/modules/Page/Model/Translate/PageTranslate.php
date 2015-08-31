<?php
    /**
     * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
     * @author Oleksandr Torosh <web@wezoom.net>
     */

namespace Page\Model\Translate;

use Application\Mvc\Model\Translate;

class PageTranslate extends Translate
{

    public function getSource()
    {
        return "page_translate";
    }

}