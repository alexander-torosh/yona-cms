<?php

/**
 * CategoryForm
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
namespace Category\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;

class CategoryForm extends Form
{

    public function initialize()
    {
        $this->add(new Hidden('parent_id'));

        $type = new Hidden('type');
        $type->setDefault('catalog');
        $this->add($type);

        $sortorder = new Text('sortorder');
        $sortorder->setLabel("Позиция");
        $this->add($sortorder);

        $title = new Text('title', array('required' => true));
        $title->setLabel("Название");
        $title->addValidator(new PresenceOf(array(
            'message' => "Название не может быть пустым"
        )));
        $this->add($title);

        $title_uk = new Text('title_uk');
        $title_uk->setLabel("Название Укр");
        $this->add($title_uk);

        $slug = new Text('slug');
        $slug->setLabel("Транслитерация");
        $this->add($slug);

        $meta_title = new Text('meta_title');
        $meta_title->setLabel('Meta Title');
        $this->add($meta_title);

        $meta_title_uk = new Text('meta_title_uk');
        $meta_title_uk->setLabel('Meta Title Укр');
        $this->add($meta_title_uk);

        $meta_description = new Text('meta_description');
        $meta_description->setLabel('Meta Description');
        $this->add($meta_description);

        $meta_description_uk = new Text('meta_description_uk');
        $meta_description_uk->setLabel('Meta Description Укр');
        $this->add($meta_description_uk);

        $meta_keywords = new Text('meta_keywords');
        $meta_keywords->setLabel('Meta Keywords');
        $this->add($meta_keywords);

        $meta_keywords_uk = new Text('meta_keywords_uk');
        $meta_keywords_uk->setLabel('Meta Keywords Укр');
        $this->add($meta_keywords_uk);

        $text = new TextArea('text');
        $text->setLabel('SEO Текст');
        $this->add($text);

        $text_uk = new TextArea('text_uk');
        $text_uk->setLabel('SEO Текст Укр');
        $this->add($text_uk);

        $preview = new File('preview');
        $this->add($preview);

        $this->add((new Check('visible', array('value' => 1)))->setLabel('Отображать'));

    }

}
