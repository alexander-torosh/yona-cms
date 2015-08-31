<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Tree\Mvc;

use Phalcon\Mvc\User\Component;
use Tree\Model\Category;

class Helper extends Component
{

    public function treeUpperLeafs($root)
    {
        return Category::treeUpperLeafs($root);
    }

}