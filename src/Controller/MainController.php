<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('main/login.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/article/{id}', name: 'article')]
    public function article(): Response
    {
        return $this->render('main/article.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('main/profile.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/sell', name: 'sell')]
    public function sell(): Response
    {
        return $this->render('main/sell.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/message', name: 'message')]
    public function message(): Response
    {
        return $this->render('main/message.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}