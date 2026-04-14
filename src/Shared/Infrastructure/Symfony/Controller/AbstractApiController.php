<?php
namespace App\Shared\Infrastructure\Symfony\Controller;

use App\Shared\Infrastructure\Symfony\Http\BadRequestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AbstractApiController extends AbstractController
{

    public function success(mixed $data = null, int $status = 200, array $headers = [], bool $json = false): JsonResponse
    {
        assert($status >= 200 && $status < 300, 'invalid success status code');

        return new JsonResponse($data, $status, $headers, $json);
    }
    public function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return new JsonResponse(new BadRequestResponse('not_found', $message, null, Response::HTTP_NOT_FOUND), Response::HTTP_NOT_FOUND);
    }

    public function badRequest(string $message, ?array $errors = null, int $status = 400): JsonResponse
    {
        assert($status >= 400 && $status < 500, 'invalid bad request status code');

        return new JsonResponse(new BadRequestResponse('bad_request', $message, $errors, $status), $status);
    }
}
