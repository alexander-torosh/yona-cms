<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Controller;

use Application\Mvc\Controller;
use Application\Mvc\Helper\CmsCache;
use Cms\Model\Translate;
use Cms\Scanner;

class TranslateController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-translate');
    }

    public function indexAction()
    {
        $model = new Translate();
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            if (!empty($post)) {
                foreach ($post as $key => $value) {
                    $key = str_replace('_', ' ', $key); // При отправке формы POST-запросом, все пробелы заменяются на "_". Меняем обратно.
                    $phrase = $model->findByPhraseAndLang($key);
                    if ($phrase) {
                        $phrase->setTranslation($value);
                        $phrase->update();
                    } else {
                        $phraseNew = new Translate();
                        $phraseNew->setPhrase($key);
                        $phraseNew->setLang(LANG);
                        $phraseNew->setTranslation($value);
                        $phraseNew->create();
                    }
                }
            }

            CmsCache::getInstance()->save('translates', Translate::buildCmsTranslatesCache());
            $this->flash->success($this->helper->at('Saved has been successful'));

            $lang = LANG;
            $key = HOST_HASH . md5("Translate::findByLang($lang)");
            $this->cache->delete($key);

            return $this->redirect($this->url->get() . 'cms/translate?lang=' . LANG);
        }

        $scanner = new Scanner();
        $phrases = $scanner->search();

        $this->view->phrases = $phrases;
        $this->view->model = $model;
    }


}