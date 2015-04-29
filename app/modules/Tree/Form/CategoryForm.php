<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Tree\Form;

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Tree\Model\Category;

class TreeForm extends \Application\Form\Form
{

    public function initialize()
    {
        $this->add(
            (new Text('title', ['required' => 'required']))
                ->addValidator(new PresenceOf([
                    'message' => 'Title is required'
                ]))
                ->setLabel('Title')
        );

        $this->add(
            (new Select('root', Category::$roots))
                ->setLabel('Root')
        );

    }

}