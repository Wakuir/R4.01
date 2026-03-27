<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\UsagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/commande', requirements: ['_locale' => '%app.supported_locales%'],
    defaults: ['_locale' => 'fr'])]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index')]
    public function index(UsagerRepository $usagers) : Response {
        $usager = $this->getUser();
        return $this->render('commande/index.html.twig', ['commandes' => $usager->getCommandes()]);
    }

    #[Route('/{id}', name: 'app_commande_commande')]
    public function commande(CommandeRepository $commandes, int $id) : Response {
        $usager = $this->getUser();
        $commande = $commandes->find($id);
        return $this->render('commande/commande.html.twig', ['prenom' => $usager->getPrenom(), 'nom' => $usager->getNom(), 'date' => $commande->getDateCreation(), 'numCommande' => $commande->getId(), 'articles' => $commande->getLigneCommandes(), 'total' => $commande->getTotal()]);
    }
}
