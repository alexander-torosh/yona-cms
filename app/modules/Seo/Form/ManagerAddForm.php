<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Seo\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Seo\Model\Manager;

class ManagerAddForm extends Form
{

	public function initialize()
	{
		$this->add(
			(new Text('custom_name'))
				->setLabel($this->helper->at('Business name, for convenience'))
		);

		$this->add(
			(new Select('type', Manager::$types))
				->setLabel('Type')
		);
	}

}