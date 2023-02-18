<?php

namespace App\Repository;

use App\Entity\Couplet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Couplet>
 *
 * @method Couplet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Couplet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Couplet[]    findAll()
 * @method Couplet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoupletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Couplet::class);
    }

    public function save(Couplet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Couplet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Couplet[] Returns an array of Couplet objects
     */
    public function findByHymnId(int $hymnId): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.couplet', 'c.position', 'c.isChorus')
            ->andWhere('c.hymn = :id')
            ->setParameter('id', $hymnId)
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Couplet
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
