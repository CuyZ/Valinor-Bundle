<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\App\Normalizer;

use CuyZ\Valinor\Normalizer\ArrayNormalizer;
use CuyZ\Valinor\Normalizer\JsonNormalizer;

final class NormalizerContainer
{
    public function __construct(
        public ArrayNormalizer $arrayNormalizer,
        public JsonNormalizer $jsonNormalizer,
    ) {}
}
