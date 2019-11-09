<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use Phalcon\Events\Manager;

class ApiEventsManager
{
    /**
     * @return Manager
     */
    public function getEventsManager(): Manager
    {
        $eventsManager = new Manager();

        // @TODO
        /*$eventsManager->attach('test:event', function(Event $event, $app) {
            var_dump($event->getData());
        });*/

        return $eventsManager;
    }
}