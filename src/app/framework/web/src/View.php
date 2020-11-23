<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Mvc\View as PhalconView;
use Phalcon\Mvc\View\Engine\Volt;

class View extends PhalconView
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->setViewsDir(getenv('ROOT_DIR').'/src/app/framework/web/modules/front/views/');
        $this->setMainView('front');

        $volt = new Volt($this, $this->getDI());
        $volt->setOptions([
            'path' => getenv('ROOT_DIR').'/data/cache/volt/',
        ]);

        $this->registerEngines([
            '.volt' => $volt,
        ]);
    }
}
