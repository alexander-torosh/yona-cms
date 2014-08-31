<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Helper;

class RequestQuery extends \Phalcon\Mvc\User\Component
{

    public function getSymbol()
    {
        $queries = $this->request->getQuery();
        if (count($queries) == 1) {
            return '?';
        } else {
            return '&';
        }
    }

} 