<?php
    /**
     * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
     * @author Oleksandr Torosh <web@wezoom.net>
     */

namespace Page\Model\Translate;

use Application\Mvc\ModelTranslate;

class PageTranslate extends ModelTranslate
{

    public function getSource()
    {
        return "page_translate";
    }

}