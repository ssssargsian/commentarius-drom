<?php

declare(strict_types=1);

namespace App\Application\Command\Commentary\UseCase\Update;

use App\Entity\Commentary;
use App\Repository\CommentaryRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class UpdateCommandHandler
{
    public function __construct(
        private CommentaryRepository $commentaryRepository,
    ) {
    }

    public function __invoke(UpdateCommand $command): Commentary
    {
        $commentary = $this->commentaryRepository->find($command->id);

        if ($command->name) {
            $commentary->setName($command->name);
        }
        if ($command->text) {
            $commentary->setText($command->text);
        }

        return $commentary;
    }
}
