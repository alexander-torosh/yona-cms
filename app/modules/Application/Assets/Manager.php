<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Application\Assets;


class Manager extends \Phalcon\Assets\Manager
{

    public function outputLess($collectionName = null)
    {
        $this->useImplicitOutput(false);
        return str_replace('stylesheet', 'stylesheet/less', $this->outputCss($collectionName));
    }

} 