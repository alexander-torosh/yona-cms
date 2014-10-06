<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Application\Mvc\Helper;

use Phalcon\Mvc\User\Component;

class LangSwitcher extends Component
{

    public function render($string, $lang)
    {
        /*$uriParts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $langParts = explode('/ru', $uriParts[0]);
        $clearUri = end($langParts);
        $view = $this->getDI()->get('view');

        if ($lang == 'ru') {
            $href = '/ru' . $clearUri;
        } else {
            $href = $clearUri;
        }

        if ($view->disabledLang == $lang) {
            return '<span>' . $string . '</span>';
        }

        return '<a href="' . $href . '">' . $string . '</a>';*/

    }

} 