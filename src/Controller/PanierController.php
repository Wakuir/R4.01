<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\UsagerRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/panier',
    requirements: ['_locale' => '%app.supported_locales%'],
    defaults: ['_locale' => 'fr'])]
class PanierController extends AbstractController
{

    #[Route('/', name: 'app_panier_index')]
    public function index(PanierService $panierService, ProduitRepository $prods): Response
    {
        return $this->render('panier/index.html.twig', [
            'panier' => $panierService->getContenu($prods),
            'montant' => $panierService->getTotal($prods),
        ]);
    }

    #[Route('/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter', defaults: ["quantite" => 1])]
    public function ajouter(PanierService $panierService, int $idProduit, int $quantite): Response
    {
        $panierService->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever', defaults: ["quantite" => 1])]
    public function enlever(PanierService $panierService, int $idProduit, int $quantite): Response
    {
        $panierService->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/supprimer/{idProduit}', name: 'app_panier_supprimer')]
    public function supprimer(PanierService $panierService, int $idProduit): Response
    {
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_panier_vider')]
    public function vider(PanierService $panierService): Response {
        $panierService->vider();
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/commander', name: 'app_panier_commander')]
    public function commander(PanierService $panierService, UsagerRepository $usagers, ProduitRepository $produits, EntityManagerInterface $entityManager): Response {
        $usager = $this->getUser();
        $commande = $panierService->panierToCommande($usager, $produits, $entityManager);
        return $this->redirectToRoute('app_commande_commande', ['id' => $commande->getId()]);
    }

    public function nombreProduits(PanierService $panier): Response {
        return new Response($panier->getNombreProduits());
    }
}
