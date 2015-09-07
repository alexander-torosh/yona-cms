<?php

namespace Sitemap\Controller;

use Application\Mvc\Controller;
use Cms\Model\Language;
use Application\Mvc\Router\DefaultRouter;


class IndexController extends Controller
{
    private $cacheViewKey;
    private $models;
    private $links = [];

    public function initialize()
    {
        $this->cacheViewKey =  HOST_HASH . md5('Sitemap\Model\Sitemap');

        $this->models = [
            [
                'class' => 'Publication',
                'model' => 'Publication',
                'where' => "", // preview_inner='0'  ,  etc.
                'getLink' => function($model, $lang){
                    return $this->langUrlCustom([
                        'for' => 'publication',
                        'type' => $model->getTypeSlug(),
                        'slug' => $model->getSlug()], $lang);
                }
            ],[
                'class' => 'Page',
                'model' => 'Page',
                'getLink'      => function($model, $lang){
                    return $this->langUrlCustom([
                        'for' => 'page',
                        'slug' => $model->getSlug()], $lang);
                },
            ]
        ];
    }


    public function indexAction()
    {
        $this->view->setRenderLevel( \Phalcon\Mvc\View::LEVEL_NO_RENDER );
        $cache = $this->getDi()->get('cache');
        $sitemap_xml = $cache->get($this->cacheViewKey);

        if(!$sitemap_xml){
            $langs = Language::find(['columns' => 'iso,primary']);

            //link(s) for main-page(s)
            foreach ($langs as $lang){
                $suffix = !$lang['primary'] ? $lang['iso'] . '/' : '';
                $this->links[] = [
                    'url' => 'http://' . $_SERVER['HTTP_HOST'] .  '/' . $suffix,
                    'updated_at' => date('c',time()),
                ];
            }

            foreach ($this->models as $m) {
                $class_name = '\\' . $m['class'] . '\Model\\' . $m['model'];
                $where      = !empty($m['where']) ? $m['where'] : '';

                $rows = $class_name::find($where);

                foreach ($langs as $lang) {
                    foreach ($rows as $row) {
                        $row::setCustomLang($lang->iso);
                        if($row->getSlug() !== 'index' && $row->getTitle()){
                            $this->links[] = [
                                'url'        => 'http://' . $_SERVER['HTTP_HOST'] .  $m['getLink']($row, $lang->iso),
                                'updated_at' => date('c', strtotime($row->getUpdatedAt())),
                            ];
                        }
                    }
                }

                $sitemap_xml = $this->getRawXml();
                $cache->save($this->cacheViewKey, $sitemap_xml);
            }
        }

        $this->response->setHeader("Content-Type", "text/xml");
        $this->response->setContent($sitemap_xml);
        return $this->response->send();
    }

    public function langUrlCustom($params, $lang)
    {
        $routeName = $params['for'];
        $routeName = DefaultRouter::ML_PREFIX . $routeName . '_' . $lang;
        $params['for'] = $routeName;
        return $this->url->get($params);
    }

    private function getRawXml()
    {
        $this->view->setRenderLevel( \Phalcon\Mvc\View::LEVEL_ACTION_VIEW );
        $this->view->links = $this->links;
        $this->view->start();
        $this->view->setLayoutsDir('../views/');
        $this->view->render('index', 'index');
        $this->view->finish();
        return $this->view->getContent();
    }

} 