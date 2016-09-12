<?php

namespace Api\Filter;

/**
 * Class PagesList.
 * Class is responsible to filter required data for pages listing.
 * 
 * @package Api\Formatter
 */
final class PagesList implements \Phalcon\Filter\UserFilterInterface
{
    /**
     * Filter required data.
     *
     * @param mixed $pages
     * @return array
     */
    public function filter($pages)
    {
        /* @var \Page\Model\Page $page */
        $data = array();
        foreach ($pages as $page) {
            $data[] = array(
                'pageId' => $page->getId(),
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

        return $data;
    }
}
