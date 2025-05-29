<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\Scalar;

use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use InvalidArgumentException;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\Email;
use Jerowork\GraphqlAttributeSchema\Attribute\Scalar;

#[Scalar(alias: Email::class)]
final class EmailType extends ScalarType
{
    public function serialize($value): string
    {
        if (!$value instanceof Email) {
            throw new InvalidArgumentException('Expected an Email value for custom scalar EmailType');
        }

        return (string) $value;
    }

    public function parseValue($value): Email
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Expected a string value for custom scalar EmailType');
        }

        return new Email($value);
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): Email
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new InvalidArgumentException('Expected a string value (node) for custom scalar EmailType');
        }

        return new Email($valueNode->value);
    }
}
