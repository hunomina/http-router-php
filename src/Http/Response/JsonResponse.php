<?php

namespace hunomina\Http\Response;

use hunomina\Http\HttpException;

class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     * @param $content
     * @param array $headers
     * @throws HttpException
     */
    public function __construct($content, array $headers = ['Content-Type: application/json'])
    {
        if (!\is_array($content)) {
            throw new HttpException('You must give an array as content parameter');
        }
        $content = json_encode($content);

        parent::__construct($content, $headers);
    }
}