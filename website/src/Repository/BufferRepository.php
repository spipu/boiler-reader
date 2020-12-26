<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Buffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Buffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Buffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Buffer[]    findAll()
 * @method Buffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BufferRepository extends ServiceEntityRepository
{
    /**
     * BufferRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Buffer::class);
    }
}
