<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Controller;

use CuyZ\Valinor\Mapper\Http\FromQuery;
use CuyZ\ValinorBundle\Configurator\DateFormatsConfigurator;
use CuyZ\ValinorBundle\Http\MapRequest;
use CuyZ\ValinorBundle\Tests\App\Configurator\AllowSuperfluousKeysConfigurator;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ControllerWithMapRequestConfigurators
{
    #[Route('/api/with-request-configurator', methods: 'GET')]
    #[MapRequest(new AllowSuperfluousKeysConfigurator(), new DateFormatsConfigurator(['Y-m-d']))]
    public function __invoke(#[FromQuery] DateTimeInterface $date): Response
    {
        return new JsonResponse([
            'date' => $date->format('Y-m-d'),
        ]);
    }
}
