<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Resolver\Query;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\AuthorRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\AuthorType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class AuthorQuery
{
    public function __construct(
        private AuthorRepository $authorRepository,
    ) {}

    #[Query]
    public function author(string $id): AuthorType
    {
        $author = $this->authorRepository->getById($id);

        return new AuthorType($author);
    }
}
