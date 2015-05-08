<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Tree\Controller;

use Application\Localization\Transliterator;
use Application\Mvc\Controller;
use Tree\Model\Category;

class AdminController extends Controller
{

    public function indexAction()
    {
        $this->setAdminEnvironment();

        $this->view->roots = Category::$roots;

    }

    public function addAction()
    {
        if (!$this->request->getPost() || !$this->request->isAjax()) {
            die('post ajax required');
        }

        $root = $this->request->getPost('root');
        $title = $this->request->getPost('title', 'string');

        $model = new Category();
        $model->setRoot($root);
        if ($model->create()) {
            $model->setTitle($title);
            $model->setSlug(Transliterator::slugify($title));
            if ($model->update()) {
                $this->returnJSON(['success' => true, 'id' => $model->getId()]);
            } else {
                $this->returnJSON(['error' => implode(' | ', $model->getMessages())]);
            }
        } else {
            $this->returnJSON(['error' => implode(' | ', $model->getMessages())]);
        }
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }

    public function saveTreeAction()
    {
        if (!$this->request->getPost() || !$this->request->isAjax()) {
            die('post ajax required');
        }

        //$root = $this->request->getPost('root');
        $data = $this->request->getPost('data');

        foreach($data as $el) {
            if ($el['item_id']) {
                $model = Category::findFirst($el['item_id']);
                if ($model) {
                    if ($el['parent_id']) {
                        $model->setParentId($el['parent_id']);
                    } else {
                        $model->setParentId(null);
                    }
                    $model->setDepth($el['depth']);
                    $model->setLeftKey($el['left']);
                    $model->setRightKey($el['right']);
                    $model->update();
                }
            }
        }

        $this->returnJSON(['success' => true]);

    }

}