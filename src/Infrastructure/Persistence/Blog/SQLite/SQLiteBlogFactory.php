<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Persistence\Blog\SQLite;

use DateTimeImmutable;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\Blog;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogStatus;

/**
 * Note: In an actual application, the use of third-party libraries as Doctrine is recommended.
 * For the sake of this Proof of Concept, it's using a simple SQLite database, as this project focuses on the GraphQL layer only.
 *
 * @phpstan-type BlogRowPayload array{
 *      id: string,
 *      status: string,
 *      title: string,
 *      author_id: string,
 *      tags: string,
 *      published_at: string
 *  }
 */
final readonly class SQLiteBlogFactory
{
    /**
     * @param BlogRowPayload $row
     */
    public static function createFromRow(array $row): Blog
    {
        /** @var list<string> $tags */
        $tags = json_decode($row['tags'], true, 512, JSON_THROW_ON_ERROR);

        return new Blog(
            $row['id'],
            BlogStatus::from($row['status']),
            $row['title'],
            $row['author_id'],
            $tags,
            new DateTimeImmutable($row['published_at']),
        );
    }
}
