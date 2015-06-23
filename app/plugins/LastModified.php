<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace YonaCMS\Plugin;

use Phalcon\Mvc\User\Plugin;

class LastModified extends Plugin
{

    public function __construct($response)
    {
        $LastModified_unix = 1294844676;
        $LastModified = gmdate('D, d M Y H:i:s \G\M\T', $LastModified_unix);
        $IfModifiedSince = false;

        if (isset($_ENV['HTTP_IF_MODIFIED_SINCE'])) {
                    $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                    $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
            header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
            exit;
        }

        $response->setHeader('Last-Modified', $LastModified);
    }

} 