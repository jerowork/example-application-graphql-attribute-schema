<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Resolver\Query;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogStatus;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\BlogStatusType;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\BlogType;
use Jerowork\GraphqlAttributeSchema\Attribute\Arg;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ConnectionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Type\Connection\Connection;
use Jerowork\GraphqlAttributeSchema\Type\Connection\EdgeArgs;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class BlogsQuery
{
    public function __construct(
        private BlogRepository $blogRepository,
    ) {}

    #[Query(type: new ConnectionType(BlogType::class))]
    public function blogs(
        EdgeArgs $edgeArgs,
        ?BlogStatusType $status,
        #[Arg(description: 'The author identifier')]
        ?string $authorId,
    ): Connection {
        $blogs = $this->blogRepository->getBlogs(
            $status !== null ? BlogStatus::from($status->value) : null,
            $authorId,
            $edgeArgs->first,
            $edgeArgs->after,
            $edgeArgs->last,
            $edgeArgs->before,
        );

        $hasPreviousPage = $this->blogRepository->getBlogs(
            $status !== null ? BlogStatus::from($status->value) : null,
            $authorId,
            null,
            null,
            $edgeArgs->last ?? $edgeArgs->first,
            $blogs->first()?->id,
        )->blogs !== [];

        $hasNextPage = $this->blogRepository->getBlogs(
            $status !== null ? BlogStatus::from($status->value) : null,
            $authorId,
            $edgeArgs->last ?? $edgeArgs->first,
            $blogs->last()?->id,
            null,
            null,
        )->blogs !== [];

        return new Connection(
            array_map(fn($blog) => new BlogType($blog), $blogs->blogs),
            $hasPreviousPage,
            $hasNextPage,
            $blogs->first()?->id,
            $blogs->last()?->id,
        );
    }
}
