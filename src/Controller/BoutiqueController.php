<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/{_locale}/boutique',
    requirements: ['_locale' => '%app.supported_locales%'],
    defaults: ['_locale' => 'fr']
)]
final class BoutiqueController extends AbstractController
{
    #[Route('/', name: 'app_boutique_index')]
    public function index(CategorieRepository $cats): Response
    {
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'categories' => $cats->findAll()
        ]);
    }

    #[Route('/rayon/{idCategorie}', name: 'app_boutique_rayon')]
    public function rayon(CategorieRepository $cats, ProduitRepository $prods, int $idCategorie): Response
    {
        return $this->render("boutique/rayon.html.twig", [
            'infoRayon' => $prods->find($idCategorie),
            'produits' => $cats->find($idCategorie)->getProduits()
        ]);
    }

    #[Route('/chercher/{recherche}',
        name: 'app_boutique_chercher',
        requirements: ['recherche' => '.+'], // regexp pour avoir tous les car, / compris
        defaults: ['recherche' => ''])]
    public function chercher(ProduitRepository $prods, string $recherche): Response
    {
        return $this->render("boutique/chercher.html.twig", [
            "recherche" => $recherche,
            "produits" => $prods->findByLibelleOrTexte($recherche)
        ]);
    }
}
