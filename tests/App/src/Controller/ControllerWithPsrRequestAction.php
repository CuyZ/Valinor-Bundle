<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Controller;

use CuyZ\ValinorBundle\Http\MapRequest;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ControllerWithPsrRequestAction
{
    #[Route('/api/psr-request', methods: 'GET')]
    #[MapRequest]
    public function __invoke(RequestInterface $request): Response
    {
        return new JsonResponse([
            'uri' => $request->getUri()->getPath(),
        ]);
    }
}
