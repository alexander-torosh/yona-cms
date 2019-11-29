<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use Phalcon\Events\Manager;

class EventsManager
{
    public function getEventsManager(): Manager
    {
        return new Manager();
        // @TODO
        /*$eventsManager->attach('test:event', function(Event $event, $app) {
            var_dump($event->getData());
        });*/
    }
}
