<?php

namespace App\Controller;

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
        $usager = $usagers->find(1);
        return $this->render('commande/index.html.twig', ['commandes' => $usager->getCommandes()]);
    }
}
