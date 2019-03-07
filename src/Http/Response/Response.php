<?php

namespace hunomina\Http\Response;

abstract class Response
{
    /** @var mixed $_content */
    protected $_content;

    /** @var array $_headers */
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
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = $this->_headers;
        $headers[] = $this->getHttpCodeHeader();
        return $headers;
    }

    /**
     * @param mixed $header
     */
    public function setHeaders($header): void
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
        $http_header = 'HTTP/1.0 ';
        switch ($this->_http_code) {
            case 301:
                $http_header .= '301 Move Permanently';
                break;
            case 302:
                $http_header .= '302 Found';
                break;
            case 403:
                $http_header .= '403 Forbidden';
                break;
            case 404:
                $http_header .= '404 Not Found';
                break;
            case 500:
                $http_header .= '500 Internal Server Error';
                break;
            default:
                $http_header .= '200 Ok';
                break;
        }
        return $http_header;
    }
}