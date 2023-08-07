<?php

declare(strict_types=1);

namespace CuyZ\ValinorBundle\Tests\QA;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function str_starts_with;

/**
 * @implements Rule<InClassNode>
 */
final class ApiAndInternalAnnotationCheck implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $scope->getClassReflection();

        if ($reflection === null) {
            return [];
        }

        if ($reflection->isAnonymous()) {
            return [];
        }

        if (str_starts_with($reflection->getName(), 'CuyZ\ValinorBundle\Tests')) {
            return [];
        }

        if (preg_match('/@(api|internal)\s+/', $reflection->getResolvedPhpDoc()?->getPhpDocString() ?? '') !== 1) {
            return [
                RuleErrorBuilder::message(
                    'Missing annotation `@api` or `@internal`.'
                )->build(),
            ];
        }

        return [];
    }
}
