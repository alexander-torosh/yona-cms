<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:48
 */

namespace Publication\Form;

use Application\Form\Element\Image;
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
        $type = new Select('type_id', Type::cachedListArray(['key' => 'id']));
        $type->setLabel('Type of Publication');
        $this->add($type);

        $title = new Text('title', ['required' => true]);
        $title->addValidator(new PresenceOf([
            'message' => 'Title can not be empty'
        ]));
        $title->setLabel('Title');
        $this->add($title);

        $slug = new Text('slug');
        $slug->setLabel('Slug');
        $this->add($slug);

        $date = new Text('date');
        $date->setLabel('Publication Date');
        $this->add($date);

        $text = new TextArea('text');
        $text->setLabel('Text');
        $this->add($text);

        $meta_title = new Text('meta_title', ['required' => true]);
        $meta_title->setLabel('meta-title');
        $this->add($meta_title);

        $meta_description = new TextArea('meta_description', ['style' => 'height:4em; min-height: inherit']);
        $meta_description->setLabel('meta-description');
        $this->add($meta_description);

        $meta_keywords = new TextArea('meta_keywords', ['style' => 'height:4em; min-height: inherit']);
        $meta_keywords->setLabel('meta-keywords');
        $this->add($meta_keywords);

        $preview_inner = new Check('preview_inner');
        $preview_inner->setLabel('Show preview image inside publication');
        $this->add($preview_inner);

        $image = new Image('preview_src');
        $image->setLabel('Thumbnail Image');
        $this->add($image);
    }

} 