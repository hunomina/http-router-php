<?php

namespace hunomina\Http\Response;

use hunomina\Http\HttpStatusCode;

abstract class Response
{
    /** @var mixed $_content */
    protected $_content;

    /** @var string[] $_headers */
    protected $_headers = [];

    /** @var int $_http_code */
    protected $_http_code = 200;

    public function __construct($content, array $headers = [])
    {
        $this->_content = $content;
        $this->setHeaders($headers);
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->_content = $content;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->_http_code;
    }

    /**
     * @param int $http_code
     */
    public function setHttpCode(int $http_code): void
    {
        $this->_http_code = $http_code;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        $headers = $this->_headers;
        $headers[] = $this->getHttpCodeHeader();
        return $headers;
    }

    /**
     * @param string[] $header
     */
    public function setHeaders(array $header): void
    {
        $this->_headers = $header;
    }

    /**
     * @param string $header
     */
    public function addHeader(string $header): void
    {
        $this->_headers[] = $header;
    }

    /**
     * @return string
     */
    public function getHttpCodeHeader(): string
    {
        return HttpStatusCode::getHttpStatusCodeHeader($this->getHttpCode());
    }
}