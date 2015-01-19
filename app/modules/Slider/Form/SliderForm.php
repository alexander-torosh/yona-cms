<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:48
 */

namespace Slider\Form;


use Application\Form\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

class SliderForm extends Form
{

    public function initialize()
    {
        $this->add(
            (new Text('title', array('required' => true)))
                ->setLabel('Internal name')
        );

        $this->add(
            (new Text('animation_speed', array('required' => true)))
                ->setLabel('Animation speed')
                ->setAttribute('placeholder', '300')
        );

        $this->add(
            (new Text('delay'))
                ->setLabel('Delay duration')
        );

        $this->add(
            (new Check('visible'))
                ->setLabel('Visible')
                ->setDefault(1)
        );

        $this->add(
            (new File('image[]', array('id' => 'file')))
                ->setLabel('Images')
                ->setAttribute('multiple', 'multiple')
        );

    }

} 