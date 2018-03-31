<?php

namespace Core\Interfaces;

interface CollectionsInterface
{
    public const METHOD_GET = 'get';
    public const METHOD_POST = 'post';


    /**
     * Structure
     *
     * [
     *    handler => [
     *       endpoint => [method => action]
     *    ]
     * ]
     *
     * example:
     * [
     *    IndexController::class => [
     *       '/post/{id:\d+}' => [CollectionsInterface::METHOD_GET => 'post']
     *    ]
     * ]
     *
     *
     * @return array
     */
    public function init(): array;
}
