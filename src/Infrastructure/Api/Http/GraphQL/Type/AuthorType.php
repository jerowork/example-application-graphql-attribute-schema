<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\Author;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\Email;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
#[Type]
final readonly class AuthorType
{
    public function __construct(
        public Author $author,
    ) {}

    #[Field]
    public function getId(): string
    {
        return $this->author->id;
    }

    #[Field(description: 'The full name of the author')]
    public function getName(): string
    {
        return $this->author->name;
    }

    #[Field]
    public function getEmail(): ?Email
    {
        return $this->author->email;
    }
}
