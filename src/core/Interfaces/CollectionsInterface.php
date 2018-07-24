<?php

namespace Core\Interfaces;

use Phalcon\Mvc\Micro\Collection;

interface CollectionsInterface
{
    /**
     * @return Collection[]
     */
    public function collections(): array;
}
