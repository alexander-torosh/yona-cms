<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Tree;

use Phalcon\Mvc\User\Component;
use Tree\Mvc\Helper;

class Init extends Component
{

    public function __construct()
    {
        $this->getDi()->set('tree_helper', new Helper());
    }

}