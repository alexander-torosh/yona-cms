<?php
    /**
     *
     * Данный фильтр является просто заглушкой. Почему-то Phalcon не хотел корректно отображать путь к собираемому файлу без применения фильтра.
     *
     * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
     * @author Oleksandr Torosh <web@wezoom.net>
     */

namespace Application\Assets\Filter;

use Phalcon\Assets\FilterInterface;

class Less implements FilterInterface
{

    public function filter($contents)
    {
        return $contents;

    }

} 