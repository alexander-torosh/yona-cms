<?php

namespace Api\Model;

/**
 * Class Payload.
 * Class represents json output that is sent to clients via REST API interface.
 *
 * Output is standardized and consists of error message (if applicable) and custom formatted content.
 * Example for index page:
 *
 * {
 *   "error": false,
 *   "content": "welcome to api."
 * }
 *
 * If there was an error, the reason should be described within error property.
 * HTTP status code is always 200, since server itself responded with no error.
 *
 * @package Api\Model
 */
class Payload
{
    private $error;

    private $content;

    public function __construct($content, $error = false)
    {
        $this->error = $error;
        $this->content = $content;
    }

    public function toArray()
    {
        return array(
            'error' => $this->error,
            'content' => $this->content
        );
    }
}