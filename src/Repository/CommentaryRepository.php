<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Commentary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Commentary>
 */
class CommentaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentary::class);
    }

    public function add(Commentary $commentary): void
    {
        $this->getEntityManager()->persist($commentary);
    }

    public function remove(Commentary $commentary): void
    {
        $this->getEntityManager()->remove($commentary);
    }
}
