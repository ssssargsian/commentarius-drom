<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation;
use App\Application\Command\Commentary\UseCase\Create\CreateCommand;
use App\Application\Command\Commentary\UseCase\Update\UpdateCommand;
use App\Repository\CommentaryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @final
 */
#[ORM\Entity(CommentaryRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            openapi: new Operation(summary: 'Получить информацию по конкретному комментарию'),
            normalizationContext: ['groups' => ['commentary:read']],
        ),
        new GetCollection(
            openapi: new Operation(summary: 'Получить информацию по комментариям'),
            normalizationContext: ['groups' => ['commentary:read']],
        ),
        new Post(
            status: Response::HTTP_CREATED,
            openapiContext: ['summary' => 'Создание комментария'],
            normalizationContext: ['groups' => ['commentary:read']],
            input: CreateCommand::class,
            output: Commentary::class,
            messenger: 'input',
        ),
        new Put(
            uriTemplate: '/commentaries/{id}',
            status: Response::HTTP_OK,
            openapiContext: ['summary' => 'Обновление комментария'],
            normalizationContext: ['groups' => ['commentary:read']],
            input: UpdateCommand::class,
            output: Commentary::class,
            messenger: 'input',
            read: false,
            validate: false,
        ),
    ],
    paginationClientEnabled: true,
    paginationEnabled: true,
)]
#[ApiFilter(OrderFilter::class, properties: ['id'])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial'])]
class Commentary
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['commentary:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 200)]
    #[Groups(['commentary:read'])]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['commentary:read'])]
    private string $text;

    public function __construct(
        string $name,
        string $text,
        ?UuidInterface $id = null
    ){
        $this->name = $name;
        $this->text = $text;
        $this->id = $id ?? Uuid::uuid7();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}