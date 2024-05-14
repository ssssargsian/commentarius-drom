<?php

declare(strict_types=1);

namespace App\Application\Command\Commentary\UseCase\Update;

use App\Shared\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateCommand implements CommandInterface
{
    #[Assert\Uuid(strict: false)]
    public string $id;
    #[Assert\Length(min: 1, max: 200)]
    public ?string $name = null;
    #[Assert\Length(min: 1, max: 4000)]
    public ?string $text = null;
}
