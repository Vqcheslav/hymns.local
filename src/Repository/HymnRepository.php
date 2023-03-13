<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Couplet;
use App\Entity\Hymn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hymn>
 *
 * @method Hymn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hymn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hymn[]    findAll()
 * @method Hymn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HymnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hymn::class);
    }

    public function save(Hymn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hymn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Hymn[] Returns an array of Hymn objects
     */
    public function findMany(?int $amount): array
    {
        return $this->createQueryBuilder('h')
            ->select('h.hymnId', 'h.title', 'k.title as category')
            ->join(Category::class, 'k', Join::WITH, 'h.category = k.category_id')
            ->orderBy('h.hymnId', 'ASC')
            ->setMaxResults($amount)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Hymn[] Returns an array of Hymn objects
     */
    public function findOne(int $hymnId): array
    {
        return $this->createQueryBuilder('h')
            ->select('h.hymnId', 'h.title', 'k.title as category')
            ->join(Category::class, 'k', Join::WITH, 'h.category = k.category_id')
            ->andWhere('h.hymnId = :id')
            ->setParameter('id', $hymnId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    public function findOneBySomeField($value): ?Hymn
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
