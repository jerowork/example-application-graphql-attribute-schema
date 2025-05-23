<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Persistence\SQLite;

use SQLite3;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Note: In an actual application, the use of third-party libraries as Doctrine is recommended.
 * For the sake of this Proof of Concept, it's using a simple SQLite database, as this project focuses on the GraphQL layer only.
 */
final readonly class SQLiteFactory
{
    public function __construct(
        #[Autowire(param: 'kernel.project_dir')]
        private string $rootPath,
    ) {}

    public function create(): SQLite3
    {
        return new SQLite3(sprintf('%s/var/sqlite.db', $this->rootPath));
    }
}
