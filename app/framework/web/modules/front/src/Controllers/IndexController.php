<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Front\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Tag;

class IndexController extends Controller
{
    public function indexAction()
    {
        Tag::prependTitle('Homepage');
    }
}