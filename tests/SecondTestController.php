<?php

namespace hunomina\Routing\Test;

use hunomina\Http\HttpException;
use hunomina\Http\Response\JsonResponse;

class SecondTestController
{
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