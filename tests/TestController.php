<?php

use hunomina\Http\HttpException;
use hunomina\Http\Response\HtmlResponse;
use hunomina\Http\Response\JsonResponse;

require __DIR__.'/../vendor/autoload.php';

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

    /**
     * @param int $id
     * @param string $name
     * @return JsonResponse
     * @throws HttpException
     */
    public function get2(int $id, string $name): JsonResponse
    {
        return new JsonResponse([
            'id' => $id,
            'name' => $name
        ]);
    }
}