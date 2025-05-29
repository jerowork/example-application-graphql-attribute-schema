<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Loader;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\AuthorRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\AuthorType;
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredType;
use Jerowork\GraphqlAttributeSchema\Type\Loader\DeferredTypeLoader;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class AuthorTypeLoader implements DeferredTypeLoader
{
    public function __construct(
        private AuthorRepository $authorRepository,
    ) {}

    /**
     * @param list<string> $references
     */
    public function load(array $references): array
    {
        return array_map(
            fn($author) => new DeferredType($author->id, new AuthorType($author)),
            $this->authorRepository->getByIds(...$references),
        );
    }
}
