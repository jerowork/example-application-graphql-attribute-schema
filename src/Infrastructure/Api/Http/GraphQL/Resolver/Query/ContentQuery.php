<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Resolver\Query;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\BlogType;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\ContentType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class ContentQuery
{
    public function __construct(
        private BlogRepository $blogRepository,
    ) {}

    #[Query]
    public function content(string $id): ContentType
    {
        // Other types can be added here, e.g. a page type
        $blog = $this->blogRepository->getById($id);

        return new BlogType($blog);
    }
}
