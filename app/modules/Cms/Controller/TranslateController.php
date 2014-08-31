<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Controller;

use Application\Mvc\Controller;
use Cms\Model\Translate;

class TranslateController extends Controller
{

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
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
            $this->flash->success('Данные обновлены');

            $lang = LANG;
            $key = HOST_HASH . md5("Translate::findByLang($lang)");
            $this->cache->delete($key);

            $this->redirect('/cms/translate?lang=' . LANG);
        }

        $phrases = $this->searchPhrasesForTranslation();
        $this->view->phrases = $phrases;
        $this->view->model = $model;
    }

    private function searchPhrasesForTranslation()
    {
        $phrases = array();
        $files = $this->rsearch(APPLICATION_PATH, "/.*\.(volt)$/");
        if ($files) {
            foreach ($files as $file) {
                $contents = file_get_contents($file);
                $pattern = "/translate\('(.*)'\)/";
                $matchesCount = preg_match_all($pattern, $contents, $matches);
                if ($matchesCount) {
                    foreach ($matches[1] as $match) {
                        if (!in_array($match, $phrases)) {
                            $phrases[] = $match;
                        }
                    }
                }
            }
        }
        return $phrases;
    }

    private function rsearch($folder, $pattern)
    {
        $dir = new \RecursiveDirectoryIterator($folder);
        $ite = new \RecursiveIteratorIterator($dir);
        $files = new \RegexIterator($ite, $pattern, \RegexIterator::GET_MATCH);
        $fileList = array();
        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }
        return $fileList;
    }

}