<?php

declare(strict_types=1);

namespace App\Application\Command\Commentary\UseCase\Create;

use App\Entity\Commentary;
use App\Repository\CommentaryRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class CreateCommandHandler
{
    public function __construct(
        private CommentaryRepository $commentaryRepository,
    ) {
    }

    public function __invoke(CreateCommand $command): Commentary
    {
        $commentary =  new Commentary(
            name: $command->name,
            text: $command->text
        );

        $this->commentaryRepository->add($commentary);

        return $commentary;
    }
}
