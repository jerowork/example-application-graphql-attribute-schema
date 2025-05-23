<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Persistence\Blog\SQLite;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\Blog;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\Blogs;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogStatus;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Persistence\SQLite\SQLiteFactory;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

/**
 * Note: In an actual application, the use of third-party libraries as Doctrine is recommended.
 * For the sake of this Proof of Concept, it's using a simple SQLite database, as this project focuses on the GraphQL layer only.
 *
 * @phpstan-import-type BlogRowPayload from SQLiteBlogFactory
 */
#[AsAlias(public: true)]
final readonly class SQLiteBlogRepository implements BlogRepository
{
    private const string DIRECTION_FORWARD = 'forward';
    private const string DIRECTION_BACKWARD = 'backward';

    public function __construct(
        private SQLiteFactory $sqliteFactory,
    ) {}

    public function getById(string $blogId): Blog
    {
        $sqlite = $this->sqliteFactory->create();

        $statement = $sqlite->prepare(
            <<<'SQL'
                SELECT * 
                FROM blog 
                WHERE id = :id
                SQL
        );

        if ($statement === false) {
            throw new RuntimeException('Failed to prepare statement');
        }

        $statement->bindValue('id', $blogId);

        $result = $statement->execute();

        if ($result === false) {
            throw new RuntimeException('Failed to execute statement');
        }

        /** @var BlogRowPayload $row */
        $row = $result->fetchArray(SQLITE3_ASSOC);

        return SQLiteBlogFactory::createFromRow($row);
    }

    public function getBlogs(
        BlogStatus $status,
        string $authorId,
        ?int $first,
        ?string $afterCursor,
        ?int $last,
        ?string $beforeCursor,
    ): Blogs {
        $sqlite = $this->sqliteFactory->create();
        $direction = $last !== null && $beforeCursor !== null ? self::DIRECTION_BACKWARD : self::DIRECTION_FORWARD;

        if ($direction === self::DIRECTION_BACKWARD) {
            $statement = $sqlite->prepare(
                <<<'SQL'
                    SELECT * FROM (
                        SELECT * 
                        FROM blog 
                        WHERE status = :status
                        AND author_id = :author_id
                        AND id > :cursor
                        ORDER BY id ASC
                        LIMIT :limit
                    ) ORDER BY id DESC
                    SQL
            );
        } else {
            if ($afterCursor === null) {
                $statement = $sqlite->prepare(
                    <<<'SQL'
                        SELECT * 
                        FROM blog 
                        WHERE status = :status
                        AND author_id = :author_id
                        ORDER BY id DESC
                        LIMIT :limit
                        SQL
                );
            } else {
                $statement = $sqlite->prepare(
                    <<<'SQL'
                        SELECT * 
                        FROM blog 
                        WHERE status = :status
                        AND author_id = :author_id
                        AND id < :cursor
                        ORDER BY id DESC
                        LIMIT :limit
                        SQL
                );
            }
        }

        if ($statement === false) {
            throw new RuntimeException('Failed to prepare statement');
        }

        $statement->bindValue('status', $status->value);
        $statement->bindValue('author_id', $authorId);

        if ($direction === self::DIRECTION_BACKWARD) {
            $statement->bindValue('cursor', $beforeCursor);
            $statement->bindValue('limit', $last);
        } else {
            if ($afterCursor !== null) {
                $statement->bindValue('cursor', $afterCursor);
            }

            $statement->bindValue('limit', $first);
        }

        $result = $statement->execute();

        if ($result === false) {
            throw new RuntimeException('Failed to execute statement');
        }

        $blogs = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            /** @var BlogRowPayload $row */
            $blogs[] = SQLiteBlogFactory::createFromRow($row);
        }

        return new Blogs($blogs);
    }

    public function save(Blog $blog): void
    {
        $sqlite = $this->sqliteFactory->create();

        $statement = $sqlite->prepare(
            <<<'SQL'
                INSERT INTO blog (
                    id, 
                    status, 
                    title, 
                    author_id, 
                    tags, 
                    published_at
                ) VALUES (
                    :id, 
                    :status, 
                    :title, 
                    :author_id, 
                    :tags, 
                    :published_at
                )
                SQL
        );

        if ($statement === false) {
            throw new RuntimeException('Failed to prepare statement');
        }

        $statement->bindValue('id', $blog->id);
        $statement->bindValue('status', $blog->status->value);
        $statement->bindValue('title', $blog->title);
        $statement->bindValue('author_id', $blog->authorId);
        $statement->bindValue('tags', json_encode($blog->tags, JSON_THROW_ON_ERROR));
        $statement->bindValue('published_at', $blog->publishedAt->format('Y-m-d H:i:s'));
        $statement->execute();
    }
}
