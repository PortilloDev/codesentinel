<?php

namespace App\Shared\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/health', name: 'health_check', methods: ['GET'])]
class HealthCheck extends AbstractApiController
{

    public function __invoke(): Response
    {
        return $this->success('OK');
    }

}
