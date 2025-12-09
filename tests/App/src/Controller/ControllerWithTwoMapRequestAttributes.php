<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Controller;

use CuyZ\ValinorBundle\Http\MapRequest;
use CuyZ\ValinorBundle\Tests\App\Http\AnotherMapRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ControllerWithTwoMapRequestAttributes
{
    #[Route('/api/two-map-request-attributes', methods: 'GET')]
    #[MapRequest]
    #[AnotherMapRequest]
    public function __invoke(): Response
    {
        return new Response();
    }
}
