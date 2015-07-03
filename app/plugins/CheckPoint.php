<?php
/**
 * @copyright Copyright (c) 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 *
 * Костыль для переадресаций index.php, index.html на соотв. роуты, пример: '/index.php/news' -> '/news'
 * Данное решение было сделано для облегчения настройки веб-сервера и выполнения SEO-требований
 */
namespace YonaCMS\Plugin;

use Phalcon\Http\Request;

class CheckPoint
{

    public function __construct(Request $request)
    {
        if (strpos($request->getURI(), 'index.php') || strpos($request->getURI(), 'index.html')) {
            header('HTTP/1.0 301 Moved Permanently');
            $replaced_url = str_replace(
                ['index.php/', 'index.php', 'index.html'],
                ['', '', ''],
                str_replace('?', '', $request->getURI())
            );
            header('Location: http://' . $request->getHttpHost() . $replaced_url);
            exit(0);
        }
    }

}