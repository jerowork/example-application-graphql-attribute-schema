<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog;

use Symfony\Component\DependencyInjection\Attribute\Exclude;

/**
 * Note: In an actual application, the domain layer contains all business logic
 * (optionally decoupled from the infrastructure layer by a command bus).
 * For the sake of this Proof of Concept, it's just a set of simple DTO's, as this project focuses on the GraphQL layer only.
 */
#[Exclude]
final readonly class Blogs
{
    /**
     * @param list<Blog> $blogs
     */
    public function __construct(
        public array $blogs,
    ) {}

    public function first(): ?Blog
    {
        return $this->blogs[array_key_first($this->blogs)] ?? null;
    }

    public function last(): ?Blog
    {
        return $this->blogs[array_key_last($this->blogs)] ?? null;
    }
}
