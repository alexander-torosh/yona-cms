<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:48
 */

namespace Publication\Form;

use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Application\Form\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use \Phalcon\Forms\Element\File;
use Publication\Model\Type;

class PublicationForm extends Form
{

    public function initialize()
    {
        $type = new Select('type_id', Type::cachedListArray(array('key' => 'id')), array());
        $type->setLabel('Тип пубикации');
        $this->add($type);

        $title = new Text('title', array('required' => true));
        $title->addValidator(new PresenceOf(array(
            'message' => 'Укажите название страницы'
        )));
        $title->setLabel('Название');
        $this->add($title);

        $slug = new Text('slug');
        $slug->setLabel('Транслитерация');
        $this->add($slug);

        $date = new Text('date');
        $date->setLabel('Дата публикации');
        $this->add($date);

        $text = new TextArea('text');
        $text->setLabel('Текст');
        $this->add($text);

        $meta_title = new Text('meta_title', array('required' => true));
        $meta_title->setLabel('meta-title');
        $this->add($meta_title);

        $meta_description = new TextArea('meta_description');
        $meta_description->setLabel('meta-description');
        $this->add($meta_description);

        $meta_keywords = new TextArea('meta_keywords');
        $meta_keywords->setLabel('meta-keywords');
        $this->add($meta_keywords);

        $preview_inner = new Check('preview_inner');
        $preview_inner->setLabel('Превью внутри публикации');
        $preview_inner->setDefault(1);
        $this->add($preview_inner);

        $image = new File('image');
        $image->setLabel('Загрузить превью');
        $this->add($image);
    }

} 