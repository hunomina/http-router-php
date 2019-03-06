<?php

namespace hunomina\Routing\Test;

use hunomina\Http\HttpException;
use hunomina\Http\Response\HtmlResponse;
use hunomina\Http\Response\JsonResponse;

class TestController
{
    /**
     * @return HtmlResponse
     */
    public function index(): HtmlResponse
    {
        return new HtmlResponse('ok');
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws HttpException
     */
    public function get(int $id): JsonResponse
    {
        return new JsonResponse(['id' => $id]);
    }
}