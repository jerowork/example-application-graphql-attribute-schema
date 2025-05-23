<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author;

use Symfony\Component\DependencyInjection\Attribute\Exclude;

/**
 * Note: In an actual application, the domain layer contains all business logic
 * (optionally decoupled from the infrastructure layer by a command bus).
 * For the sake of this Proof of Concept, it's just a set of simple DTO's, as this project focuses on the GraphQL layer only.
 */
#[Exclude]
final readonly class Author
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $email,
    ) {}
}
