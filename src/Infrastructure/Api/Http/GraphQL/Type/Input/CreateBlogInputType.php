<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\Input;

use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\InputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
#[InputType]
final readonly class CreateBlogInputType
{
    /**
     * @param list<string> $tags
     */
    public function __construct(
        #[Field]
        public string $title,
        #[Field]
        public string $authorId,
        #[Field(type: new ListType(ScalarType::String))]
        public array $tags,
    ) {}
}
