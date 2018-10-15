<?php

namespace hunomina\Http\Response;

class HtmlResponse extends Response
{
    public function __construct($content, array $headers = ['Content-Type: text/html'])
    {
        parent::__construct($content, $headers);
    }
}