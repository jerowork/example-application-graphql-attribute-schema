<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Loader\AuthorTypeLoader;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InterfaceType;

#[InterfaceType]
interface ContentType
{
    #[Field(type: AuthorType::class, deferredTypeLoader: AuthorTypeLoader::class)]
    public function getAuthor(): string;
}
