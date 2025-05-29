<?php

declare(strict_types=1);

namespace Jerowork\ExampleApplicationGraphqlAttributeSchema\Domain\Author;

use InvalidArgumentException;
use Stringable;

final readonly class Email implements Stringable
{
    public function __construct(
        public string $email,
    ) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
