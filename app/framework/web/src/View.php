<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Mvc\View as PhalconView;
use Phalcon\Mvc\View\Engine\Volt;

class View extends PhalconView
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->setViewsDir(getenv('ROOT_DIR') . '/app/framework/web/view');
        $this->setMainView('main');

        $volt = new Volt($this, $this->getDI());
        $volt->setOptions([
            'compiledPath' => getenv('ROOT_DIR') . '/cache/volt/',
        ]);

        $this->registerEngines([
            '.volt'  => $volt,
        ]);
    }
}