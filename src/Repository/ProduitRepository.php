<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByLibelleOrTexte(string $recherche): array {
        return array_filter(
            $this->findAll(),
            function ($produit) use ($recherche) {
                return ($recherche == "" ||
                    mb_strpos(mb_strtolower($produit->getLibelle()) . " " . $produit->getTexte(), mb_strtolower($recherche)) !== false);
            }
        );
    }
}
