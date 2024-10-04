<?php

namespace App\Controller;

use App\Service\CallRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct(private CallRequest $callRequest, Environment $twig)
    {
        $notificationCount = $this->callRequest->GetNotificationCount();

        $twig->addGlobal('notification_count', $notificationCount);
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $allArticle = $this->callRequest->GetAllArticle();

        return $this->render('main/home.html.twig', [
            'all_article' => $allArticle
        ]);
    }
}
