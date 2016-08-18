<?php

/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace YonaCMS\Plugin;

class MobileDetect
{

    public function __construct($session, $view, $request)
    {
        $detect = new \Mobile_Detect();

        $mobile = $request->getQuery('mobile');
        if ($mobile == 'false') {
            $session->set('device_detect', 'normal');
        }
        if ($mobile == 'true') {
            $session->set('device_detect', 'mobile');
        }

        $isMobile = false;
        $device_detect = $session->get('device_detect');
        if (!empty($device_detect)) {
            $isMobile = ($device_detect == 'mobile') ? true : false;
        } else {
            if ($detect->isMobile() && !$detect->isTablet()) {
                $isMobile = true;
                $session->set('device_detect', 'mobile');
            } else {
                $session->set('device_detect', 'normal');
            }
        }

        define('MOBILE_DEVICE', ($isMobile) ? true : false);

        if (MOBILE_DEVICE) {
            $view->setMainView(MAIN_VIEW_PATH . 'mobile');
        }
    }

} 