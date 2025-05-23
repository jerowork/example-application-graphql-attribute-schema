<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\Controller;

use GraphQL\Error\DebugFlag;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;
use Jerowork\GraphqlAttributeSchema\ParserFactory;
use Jerowork\GraphqlAttributeSchema\SchemaBuilderFactory;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class GraphQLServerController
{
    public function __construct(
        private ContainerInterface $container,
        private HttpMessageFactoryInterface $httpMessageFactory,
        private ParserFactory $parserFactory,
        private SchemaBuilderFactory $schemaBuilderFactory,
    ) {}

    #[Route('/graphql', name: 'graphql.server', methods: Request::METHOD_POST)]
    public function __invoke(Request $request): Response
    {
        // 1. Parse GraphQL schema
        $ast = $this->parserFactory->create()->parse(__DIR__ . '/../GraphQL');
        $schema = $this->schemaBuilderFactory->create($this->container)->build($ast);

        // 2. Create GraphQL server
        $server = new StandardServer(ServerConfig::create([
            'schema' => $schema,
        ]));

        // 3. Handle request
        /** @var ExecutionResult|ExecutionResult[] $result */
        $result = $server->executePsrRequest(
            $this->httpMessageFactory
                ->createRequest($request)
                ->withParsedBody((array) json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR)),
        );

        // 4. Handle batch requests
        if (is_array($result)) {
            return new JsonResponse(array_map(fn($res) => $res->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE), $result));
        }

        // 5. Return response
        return new JsonResponse($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE));
    }
}
