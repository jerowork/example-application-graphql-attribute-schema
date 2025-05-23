<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

/**
 * Note: In an actual application, the domain layer contains all business logic
 * (optionally decoupled from the infrastructure layer by a command bus).
 * For the sake of this Proof of Concept, it's just a set of simple DTO's, as this project focuses on the GraphQL layer only.
 */
#[Exclude]
final readonly class Blog
{
    /**
     * @param list<string> $tags
     */
    public function __construct(
        public string $id,
        public BlogStatus $status,
        public string $title,
        public string $authorId,
        public array $tags,
        public DateTimeImmutable $publishedAt,
    ) {}

    /**
     * @param list<string> $tags
     */
    public static function createDraft(
        string $title,
        string $authorId,
        array $tags,
    ): self {
        return new self(
            (string) Uuid::uuid7(),
            BlogStatus::Draft,
            $title,
            $authorId,
            $tags,
            new DateTimeImmutable(),
        );
    }
}
