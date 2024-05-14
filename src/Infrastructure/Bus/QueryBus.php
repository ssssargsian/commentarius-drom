<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Shared\Query\QueryBusInterface;
use App\Shared\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $queryBus,
    ) {
    }

    public function execute(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
