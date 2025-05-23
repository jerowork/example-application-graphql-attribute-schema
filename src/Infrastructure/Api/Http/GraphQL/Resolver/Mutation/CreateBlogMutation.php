<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Resolver\Mutation;

use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\Blog;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Blog\BlogRepository;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\BlogType;
use Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type\Input\CreateBlogInputType;
use Jerowork\GraphqlAttributeSchema\Attribute\Mutation;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class CreateBlogMutation
{
    public function __construct(
        private BlogRepository $blogRepository,
    ) {}

    #[Mutation]
    public function createBlog(CreateBlogInputType $input): BlogType
    {
        $blog = Blog::createDraft(
            $input->title,
            $input->authorId,
            $input->tags,
        );

        $this->blogRepository->save($blog);

        return new BlogType($blog);
    }
}
