<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Controller;

use CuyZ\Valinor\Mapper\Http\FromQuery;
use CuyZ\Valinor\Mapper\Http\FromRoute;
use CuyZ\ValinorBundle\Http\MapRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class ListArticleAction
{
    #[Route('/api/authors/{authorId}/article', methods: 'GET')]
    #[MapRequest]
    public function __invoke(
        #[FromRoute] int $authorId,
        #[FromQuery] string $status,
        #[FromQuery] string $sortOrder,
        Request $request,
    ): Response {
        return new JsonResponse([
            'authorId' => $authorId,
            'status' => $status,
            'sort' => $sortOrder,
            'uri' => $request->getRequestUri(),
        ]);
    }
}
