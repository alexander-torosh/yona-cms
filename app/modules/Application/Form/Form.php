<?php

/**
 * Form
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Form;

use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Check;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Forms\Element\File;

abstract class Form extends \Phalcon\Forms\Form
{

    public function renderDecorated($name)
    {
        if (!$this->has($name)) {
            return "form element '$name' not found<br />";
        }

        $element = $this->get($name);

        /** @var \Application\Mvc\Helper $helper */
		$helper = $this->getDI()->get('helper');

        $messages = $this->getMessagesFor($element->getName());

        $html = '';

        if (count($messages)) {
            $html .= '<div class="ui error message">';
            $html .= '<div class="header">' . $this->helper->translate('Ошибка валидации формы') . '</div>';
            foreach ($messages as $message) {
                $html .= '<p>' . $message . '</p>';
            }
            $html .= '</div>';
        }

        if ($element instanceof Hidden) {
            echo $element;
        } else {
            switch (true) {
                case $element instanceof Check :
                {
                    $html .= '<div class="field">';
                    $html .= '<div class="ui toggle checkbox">';
                    $html .= $element;
                    if ($element->getLabel()) {
                        $html .= '<label>';
                        $html .= $element->getLabel();
                        $html .= '</label>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                }
                    break;
                case $element instanceof File :
                {
                    $html .= '<div class="inline field">';
                    if ($element->getLabel()) {
                        $html .= '<label for="' . $element->getName() . '">' . $helper->translate($element->getLabel()) . '</label>';
                    }
                    $html .= $element;
                    $html .= '</div>';
                }
                    break;
                default :
                    {
                    $html .= '<div class="field">';
                    if ($element->getLabel()) {
                        $html .= '<label for="' . $element->getName() . '">' . $helper->translate($element->getLabel()) . '</label>';
                    }
                    $html .= $element;
                    $html .= '</div>';
                    }
            }
        }

        return $html;

    }

    public function renderAll()
    {
        $html = '';
        if ($this->getElements()) {
            foreach ($this->getElements() as $element) {
                $html .= $this->renderDecorated($element->getName());
            }
        }
        return $html;
    }

}
