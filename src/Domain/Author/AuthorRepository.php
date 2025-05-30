<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author;

/**
 * Note: In an actual application, the domain layer contains all business logic
 * (optionally decoupled from the infrastructure layer by a command bus).
 * For the sake of this Proof of Concept, it's just a set of simple DTO's, as this project focuses on the GraphQL layer only.
 */
interface AuthorRepository
{
    public function getById(string $authorId): Author;

    /**
     * @return list<Author>
     */
    public function getByIds(string ...$authorIds): array;

    public function save(Author $author): void;
}
