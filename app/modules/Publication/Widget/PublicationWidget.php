<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Publication\Widget;

use Application\Widget\AbstractWidget;

class PublicationWidget extends AbstractWidget
{

    public function last($limit = 5)
    {
        $qb = $this->modelsManager->createBuilder();
    }

} 