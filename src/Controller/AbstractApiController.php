<?php

namespace Vundb\ServiceBundle\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractApiController extends AbstractController
{
    public function notFound(): JsonResponse
    {
        return $this->json([
            'message' => '🤷‍♂️'
        ], 404);
    }

    public function error(Exception $ex)
    {
        return $this->json([
            'message' => '🚫',
            'reason' => $ex->getMessage()
        ], 400);
    }

    public function success(mixed $data): JsonResponse
    {
        return $this->json([
            'message' => '👍',
            'data' => $data
        ]);
    }
}
