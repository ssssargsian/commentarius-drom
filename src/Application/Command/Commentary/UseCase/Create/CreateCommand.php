<?php

declare(strict_types=1);

namespace App\Application\Command\Commentary\UseCase\Create;

use App\Shared\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Length(min: 1, max: 200)]
        public string $name,
        #[Assert\Length(min: 1, max: 4000)]
        public string $text,
    ) {
    }
}
