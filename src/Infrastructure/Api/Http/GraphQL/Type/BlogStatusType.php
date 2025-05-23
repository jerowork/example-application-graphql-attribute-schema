<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Infrastructure\Api\Http\GraphQL\Type;

use Jerowork\GraphqlAttributeSchema\Attribute\Enum;
use Jerowork\GraphqlAttributeSchema\Attribute\EnumValue;

#[Enum]
enum BlogStatusType: string
{
    case Draft = 'draft';

    #[EnumValue(description: 'A published blog is visible to the client')]
    case Published = 'published';

    #[EnumValue(deprecationReason: 'This status is not used anymore')]
    case Archived = 'archived';
}
