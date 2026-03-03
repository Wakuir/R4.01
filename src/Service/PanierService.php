<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\BoutiqueService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// Service pour manipuler le panier et le stocker en session
class PanierService
{
    ////////////////////////////////////////////////////////////////////////////
    private $session;   // Le service session
    private $panier;    // Tableau associatif, la clé est un idProduit, la valeur associée est une quantité
    //   donc $this->panier[$idProduit] = quantité du produit dont l'id = $idProduit
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session pour faire persister $this->panier

    // Constructeur du service
    public function __construct(RequestStack $requestStack)
    {
        // Récupération du service session
        $this->session = $requestStack->getSession();
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $this->session->get(self::PANIER_SESSION, []);
    }

    // Renvoie le montant total du panier
    public function getTotal(ProduitRepository $prods): float
    {
        $total = 0.0;
        foreach ($this->panier as $idProduit => $quantite) {
            $produit = $prods->find($idProduit);
            if ($produit) {
                $total += $produit->getPrix() * $quantite;
            }
        }

        return $total;
    }

    // Renvoie le nombre de produits dans le panier
    public function getNombreProduits(): int
    {
        return count($this->panier);
    }

    // Ajouter au panier le produit $idProduit en quantite $quantite
    public function ajouterProduit(int $idProduit, int $quantite = 1): void
    {
        if (isset($this->panier[$idProduit])) {
            $this->panier[$idProduit] += $quantite;
        } else {
            $this->panier[$idProduit] = $quantite;
        }

        // Mettre à jour le panier en session
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    // Enlever du panier le produit $idProduit en quantite $quantite
    public function enleverProduit(int $idProduit, int $quantite = 1): void
    {
        if (isset($this->panier[$idProduit])) {
            $this->panier[$idProduit] -= $quantite;
            // Si la quantité devient nulle ou négative, on supprime le produit du panier
            if ($this->panier[$idProduit] <= 0) {
                unset($this->panier[$idProduit]);
            }
            // Mettre à jour le panier en session
            $this->session->set(self::PANIER_SESSION, $this->panier);
        }
    }

    // Supprimer le produit $idProduit du panier
    public function supprimerProduit(int $idProduit): void
    {
        if (isset($this->panier[$idProduit])) {
            unset($this->panier[$idProduit]);
            // Mettre à jour le panier en session
            $this->session->set(self::PANIER_SESSION, $this->panier);
        }
    }

    // Vider complètement le panier
    public function vider(): void
    {
        $this->panier = [];
        // Mettre à jour le panier en session
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    // Renvoie le contenu du panier dans le but de l'afficher
    //   => un tableau d'éléments [ "produit" => un objet produit, "quantite" => sa quantite ]
    public function getContenu(ProduitRepository $prods): array
    {
        $contenu = [];
        foreach ($this->panier as $idProduit => $quantite) {
            $produit = $prods->find($idProduit);
            if ($produit) {
                $contenu[] = [
                    'produit' => $produit,
                    'quantite' => $quantite
                ];
            }
        }

        return $contenu;
    }

}
