<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Http;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Tree\Message\Messages;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use function lcfirst;

/** @internal */
final class HttpRequestMappingError extends RuntimeException implements MappingError, HttpExceptionInterface
{
    public function __construct(
        private MappingError $mappingError,
    ) {
        $error = 'HTTP request is invalid, a total of ' . count($mappingError->messages()) . ' error(s) were found:';

        foreach ($mappingError->messages() as $message) {
            $error .= "\n- {$message->path()}: " . lcfirst($message->toString());
        }

        parent::__construct("$error\n");
    }

    public function getStatusCode(): int
    {
        return 422;
    }

    /**
     * @return array<mixed>
     */
    public function getHeaders(): array
    {
        return [];
    }

    public function messages(): Messages
    {
        return $this->mappingError->messages();
    }

    public function type(): string
    {
        return $this->mappingError->type();
    }

    public function source(): mixed
    {
        return $this->mappingError->source();
    }
}
