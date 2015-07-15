<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Form;


use Application\Form\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

class ManagerForm extends Form
{

    public function initialize()
    {
        $this->add((new Text('url', ['required' => 'required']))->setLabel('URL'));
        $this->add((new Text('head_title'))->setLabel('&lt;title&gt;'));
        $this->add((new TextArea('meta_description', ['style' => 'height: 4em; min-height: inherit']))->setLabel('&lt;meta name="description"&gt;'));
        $this->add((new TextArea('meta_keywords', ['style' => 'height: 4em; min-height: inherit']))->setLabel('&lt;meta name="keywords"&gt;'));
        $this->add((new TextArea('seo_text'))->setLabel('SEO Text in bottom of page'));
    }

} 