<?php

namespace App\Controller;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class TestController
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ServerRequestInterface $request): array
    {
        $this->logger->info(self::class . ' called');

        return [
            'handled' => true
        ];
    }
}
