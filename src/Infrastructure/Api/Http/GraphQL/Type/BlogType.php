<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type;

use DateTimeImmutable;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author\AuthorRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\Blog;
use Jerowork\GraphqlAttributeSchema\Attribute\Autowire;
use Jerowork\GraphqlAttributeSchema\Attribute\Cursor;
use Jerowork\GraphqlAttributeSchema\Attribute\Field;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\ScalarType;
use Jerowork\GraphqlAttributeSchema\Attribute\Type;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
#[Type]
final readonly class BlogType
{
    public function __construct(
        public Blog $blog,
    ) {}

    #[Cursor]
    #[Field]
    public function getId(): string
    {
        return $this->blog->id;
    }

    #[Field]
    public function getStatus(): BlogStatusType
    {
        return BlogStatusType::from($this->blog->status->value);
    }

    #[Field]
    public function getTitle(): string
    {
        return $this->blog->title;
    }

    #[Field]
    public function getAuthor(
        #[Autowire]
        AuthorRepository $authorRepository,
    ): AuthorType {
        return new AuthorType($authorRepository->getById($this->blog->authorId));
    }

    /**
     * @return list<string>
     */
    #[Field(type: new ListType(ScalarType::String))]
    public function getTags(): array
    {
        return $this->blog->tags;
    }

    #[Field]
    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->blog->publishedAt;
    }
}
