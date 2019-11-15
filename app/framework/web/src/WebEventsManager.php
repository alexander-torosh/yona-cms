<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Events\Manager;

class WebEventsManager
{
    /**
     * @return Manager
     */
    public function init(): Manager
    {
        $eventsManager = new Manager();

        // @TODO
        /*$eventsManager->attach('test:event', function(Event $event, $app) {
            var_dump($event->getData());
        });*/

        return $eventsManager;
    }
}