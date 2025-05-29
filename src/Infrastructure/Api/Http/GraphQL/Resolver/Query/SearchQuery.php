<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Resolver\Query;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\AuthorRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogNotFoundException;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\AuthorType;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\BlogType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class SearchQuery
{
    public function __construct(
        private BlogRepository $blogRepository,
        private AuthorRepository $authorRepository,
    ) {}

    #[Query(description: 'Search for an author or blog')]
    public function search(string $id): AuthorType|BlogType
    {
        try {
            $blog = $this->blogRepository->getById($id);

            return new BlogType($blog);
        } catch (BlogNotFoundException) {
            $author = $this->authorRepository->getById($id);

            return new AuthorType($author);
        }
    }
}
