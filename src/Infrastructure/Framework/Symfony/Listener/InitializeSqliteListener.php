<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Framework\Symfony\Listener;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Persistence\SQLite\SQLiteFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Note: In an actual application, the use of third-party libraries as Doctrine is recommended.
 * For the sake of this Proof of Concept, it's using a simple SQLite database, as this project focuses on the GraphQL layer only.
 */
final readonly class InitializeSqliteListener implements EventSubscriberInterface
{
    public function __construct(
        private SQLiteFactory $sqliteFactory,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(): void
    {
        $sqlite = $this->sqliteFactory->create();

        $sqlite->exec(
            <<<'SQL'
                CREATE TABLE IF NOT EXISTS author (
                    id TEXT PRIMARY KEY, 
                    name TEXT NOT NULL, 
                    email TEXT NULL
                )
                SQL
        );

        $sqlite->exec(
            <<<'SQL'
                CREATE TABLE IF NOT EXISTS blog (
                    id TEXT PRIMARY KEY,
                    title TEXT NOT NULL,
                    author_id TEXT NOT NULL, 
                    status TEXT NOT NULL,
                    tags TEXT NOT NULL,
                    published_at DATETIME NOT NULL
                )
                SQL
        );
    }
}
