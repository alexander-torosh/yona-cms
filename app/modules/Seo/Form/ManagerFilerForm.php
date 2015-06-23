<?php
 /**
  * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
  * @author Oleksandr Torosh <web@wezoom.net>
  */

namespace Seo\Form;


class ManagerFilerForm extends Form
{

	public function initialize()
	{
		$this->add((new Text('module'))->setLabel('Module'));
		$this->add((new Text('controller'))->setLabel('Controller'));
		$this->add((new Text('action'))->setLabel('Action'));
	}

} 