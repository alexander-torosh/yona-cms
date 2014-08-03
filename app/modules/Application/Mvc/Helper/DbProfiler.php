<?php

/**
 * DbProfiler
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Helper;

class DbProfiler extends \Phalcon\Mvc\User\Component
{

    public function DbOutput()
    {
        $profiler = $this->getDi()->get('profiler');

        $this->view->start();
        $this->view->partial('profiler', array(
            'profiler'    => $profiler,
        ));
        $html = ob_get_contents();
        $this->view->finish();

        return $html;

    }

}
