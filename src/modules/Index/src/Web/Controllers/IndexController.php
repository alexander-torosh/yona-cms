<?php
/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Index\Web\Controllers;

use Core\Controllers\ControllerAbstract;
use Phalcon\Tag;

class IndexController extends ControllerAbstract
{
    public function indexAction()
    {
        Tag::prependTitle('Homepage');
    }
}