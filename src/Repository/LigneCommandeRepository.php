<?php

namespace App\Repository;

use App\Entity\LigneCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LigneCommande>
 */
class LigneCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneCommande::class);
    }

    //    /**
    //     * @return LigneCommande[] Returns an array of LigneCommande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LigneCommande
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findTopNProduits(int $n) : array {
        //'SELECT l.produit, sum(l.quantite) as tot from App\Entity\LigneCommande l group by l.produit order by tot limi $n'

        return $this->createQueryBuilder('l')
            ->select('p.libelle as libelle, p.visuel as visuel, sum(l.quantite) as total')
            ->innerJoin('l.produit', 'p')
            ->groupBy('l.produit')
            ->orderBy('total', 'DESC')
            ->setMaxResults($n)
            ->getQuery()
            ->getArrayResult();
    }
}
