<?php

namespace App\Controller;

use App\Service\CallRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(CallRequest $callRequest): Response
    {
        $allArticle = $callRequest->GetAllArticle();

        return $this->render('main/home.html.twig', [
            'all_article' => $allArticle
        ]);
    }
}
