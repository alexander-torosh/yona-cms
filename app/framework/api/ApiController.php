<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class ApiController extends Controller
{
    public function json($contents)
    {
        // @var $response Response
        $response = $this->getDI()->get('response');
        $response->setJsonContent($contents);

        return $response->send();
    }
}
