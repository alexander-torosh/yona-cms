<?php

namespace Api\Filter;

/**
 * Class Page.
 * Class is responsible to filter required data for Page entity.
 * 
 * @package Api\Formatter
 */
final class Page implements \Phalcon\Filter\UserFilterInterface
{
    public function filter($page)
    {
        /* @var \Page\Model\Page $page */
        return array(
            'title' => $page->getTitle(),
            'slug' => $page->getSlug(),
            'source' => $page->getSource(),
            'metaTitle' => $page->getMetaTitle(),
            'metaDescription' => $page->getMetaDescription(),
            'metaKeywords' => $page->getMetaKeywords(),
            'content' => $page->getText(),
            'createdAt' => $page->getCreatedAt(),
            'updatedAt' => $page->getUpdatedAt(),
        );
    }
}
