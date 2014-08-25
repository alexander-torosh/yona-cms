<?php

namespace Video\Controller;

use Application\Mvc\Controller;
use Video\Model\Video;

class IndexController extends Controller
{

    public function indexAction()
    {
        $id = (int) $this->dispatcher->getParam('id','int');

        $video = Video::findFirst($id);

        $videos = Video::find(array(
            'order' => 'sortorder ASC',
        ));

        $this->helper->title()->append($video->getTitle());

        $this->view->video = $video;
        $this->view->videos = $videos;
    }

} 