<?php

namespace App\Controller;

use App\Repository\LigneCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController {

    public function plusVendus(LigneCommandeRepository $ligneCommandeRepository, int $n = 3) : Response {
        $articles = $ligneCommandeRepository->findTopNProduits($n);

        return $this->render('Article/plusVendus.html.twig', ['articles' => $articles]);
    }
}
