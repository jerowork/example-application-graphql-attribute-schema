<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Resolver\Mutation;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\Author;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\AuthorRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\Email;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\AuthorType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class CreateAuthorMutation
{
    public function __construct(
        private AuthorRepository $authorRepository,
    ) {}

    #[Mutation(description: 'Create an author')]
    public function createAuthor(string $name, ?Email $email): AuthorType
    {
        $author = new Author(
            (string) Uuid::uuid7(),
            $name,
            $email,
        );

        $this->authorRepository->save($author);

        return new AuthorType($author);
    }
}
