<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Text;

class LanguageForm extends Form
{

    public function initialize()
    {
        $this->add((new Text('iso', array(
            'data-description' => 'Language code according to standard ISO. For example: en',
            'required' => true
        )))->setLabel('ISO'));

        $this->add((new Text('locale', array(
            'data-description' => 'Designation locale language standard ISO. For example: en_EN',
            'required' => true
        )))->setLabel('Locale'));

        $this->add((new Text('name', array(
            'data-description' => 'For example: English',
            'required' => true
        )))->setLabel('Language'));

        $this->add((new Text('short_name', array(
            'data-description' => 'For example: Eng',
            'required' => true
        )))->setLabel('Shorting name'));

        $this->add((new Text('url', array(
            'data-description' => 'The URL-prefix string of the page. For example: en. For the "main language" is not considered to generate a URL',
            'required' => true
        )))->setLabel('URL-prefix'));

        $this->add((new Text('sortorder'))->setLabel('Sort order'));

        $this->add((new Check('primary'))->setLabel('Is primary'));
    }

} 