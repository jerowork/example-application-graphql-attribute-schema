<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Persistence\Author\SQLite;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\Author;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\AuthorRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\Email;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Persistence\SQLite\SQLiteFactory;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

/**
 * Note: In an actual application, the use of third-party libraries as Doctrine is recommended.
 * For the sake of this Proof of Concept, it's using a simple SQLite database, as this project focuses on the GraphQL layer only.
 */
#[AsAlias(public: true)]
final readonly class SQLiteAuthorRepository implements AuthorRepository
{
    public function __construct(
        private SQLiteFactory $sqliteFactory,
    ) {}

    public function getById(string $authorId): Author
    {
        $sqlite = $this->sqliteFactory->create();

        $statement = $sqlite->prepare(
            <<<'SQL'
                SELECT * 
                FROM author 
                WHERE id = :id
                SQL
        );

        if ($statement === false) {
            throw new RuntimeException('Failed to prepare statement');
        }

        $statement->bindValue('id', $authorId);

        $result = $statement->execute();

        if ($result === false) {
            throw new RuntimeException('Failed to execute statement');
        }

        /**
         * @var array{
         *     id: string,
         *     name: string,
         *     email: null|string
         * } $row
         */
        $row = $result->fetchArray(SQLITE3_ASSOC);

        return new Author(
            $row['id'],
            $row['name'],
            $row['email'] !== null ? new Email($row['email']) : null,
        );
    }

    public function getByIds(string ...$authorIds): array
    {
        $sqlite = $this->sqliteFactory->create();

        $idBinds = [];
        for ($i = 0; $i < count($authorIds); ++$i) {
            $idBinds[] = sprintf(':id_%d', $i);
        }

        $idBinds = implode(', ', $idBinds);

        $statement = $sqlite->prepare(
            <<<SQL
                SELECT * 
                FROM author 
                WHERE id IN({$idBinds})
                SQL
        );

        if ($statement === false) {
            throw new RuntimeException('Failed to prepare statement');
        }

        $i = 0;
        foreach ($authorIds as $authorId) {
            $statement->bindValue(sprintf('id_%d', $i), $authorId);
            ++$i;
        }

        $result = $statement->execute();

        if ($result === false) {
            throw new RuntimeException('Failed to execute statement');
        }

        $authors = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            /**
             * @var array{
             *     id: string,
             *     name: string,
             *     email: null|string
             * } $row
             */
            $authors[] = new Author(
                $row['id'],
                $row['name'],
                $row['email'] !== null ? new Email($row['email']) : null,
            );
        }

        return $authors;
    }

    public function save(Author $author): void
    {
        $sqlite = $this->sqliteFactory->create();

        if ($author->email !== null) {
            $statement = $sqlite->prepare(
                <<<'SQL'
                    INSERT INTO author (
                        id, 
                        name,
                        email
                    ) VALUES (
                        :id, 
                        :name, 
                        :email
                    )
                    SQL
            );
        } else {
            $statement = $sqlite->prepare(
                <<<'SQL'
                    INSERT INTO author (
                        id, 
                        name
                    ) VALUES (
                        :id, 
                        :name
                    )
                    SQL
            );
        }

        if ($statement === false) {
            throw new RuntimeException('Failed to prepare statement');
        }

        $statement->bindValue('id', $author->id);
        $statement->bindValue('name', $author->name);

        if ($author->email !== null) {
            $statement->bindValue('email', (string) $author->email);
        }

        $statement->execute();
    }
}
