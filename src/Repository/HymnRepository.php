<?php

namespace App\Repository;

use App\Entity\Category;
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
    public function findMany(int $amount = 100): array
    {
        return $this->createQueryBuilder('h')
            ->select('h.hymnId', 'h.title', 'h.chorus', 'h.couplets', 'c.title as category_title')
            ->join(Category::class, 'c', Join::WITH, 'h.category=c.category_id')
            ->orderBy('h.hymnId', 'ASC')
            ->setMaxResults($amount)
            ->getQuery()
            ->getResult()
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
