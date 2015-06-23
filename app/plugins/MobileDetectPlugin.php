<?php

/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */
class MobileDetectPlugin
{

	public function __construct($session, $view)
	{
		require_once APPLICATION_PATH . '/../vendor/Mobile_Detect.php';
		$detect = new Mobile_Detect();

		if (isset($_GET['mobile'])) {
			if ($_GET['mobile'] == 'false') {
				$session->set('device_detect', 'normal');
			}
			if ($_GET['mobile'] == 'true') {
				$session->set('device_detect', 'mobile');
			}
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