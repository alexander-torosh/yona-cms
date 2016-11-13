<?php
/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Page\Model\Helper;

use Application\Cache\Keys;
use Page\Model\Page;

class PageHelper extends Page
{

    public function pageBySlug($slug, $lang = null, $lifeTime = 60)
    {
        $lang = ($lang) ? $lang : LANG;

        $pageResult = $this->getDi()->get('cacheManager')->load([
            Keys::PAGE,
            $slug,
            $lang

        ], function() use ($slug, $lang, $lifeTime) {
            $columns = ['p.*'];
            foreach($this->translateFields as $field) {
                $columns[] = "(SELECT t.value FROM [$this->translateModel] AS t WHERE t.foreign_id = p.id AND t.lang = '$lang' AND t.key = '$field') AS $field";
            }

            $qb = $this->modelsManager->createBuilder()
                ->columns($columns)
                ->addFrom('Page\Model\Page', 'p')
                ->where('p.slug = :slug:', ['slug' => $slug]);

            $result = $qb->getQuery()->execute()->getFirst();
            return $result;

        }, $lifeTime);

        return $pageResult;
    }

}