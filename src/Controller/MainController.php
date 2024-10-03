<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\FormSellType;
use App\Service\CallRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    public function __construct(
        private CallRequest $callRequest,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $allArticle = $this->callRequest->GetAllArticle();

        return $this->render('main/home.html.twig', [
            'all_article' => $allArticle
        ]);
    }

    #[Route('/category/{category}', name: 'category')]
    public function category(string $category): Response
    {
        $allArticle = $this->callRequest->GetAllArticleByCategory($category);

        return $this->render('main/home.html.twig', [
            'all_article' => $allArticle
        ]);
    }

    #[Route('/article/{id}', name: 'article')]
    public function article(int $id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        return $this->render('main/article.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('main/profile.html.twig', [
            'user' => $user
        ]);
    }


    #[Route('/sell', name: 'sell')]
    public function sell(Request $request): Response
    {
        $sellArticle = new Article();
        $form = $this->createForm(FormSellType::class, $sellArticle);
        $form->handleRequest($request);

        return $this->render('main/sell.html.twig', [
            'form_sell' => $form,
        ]);
    }

    #[Route('/message', name: 'message')]
    public function message(): Response
    {
        $messageAllList = $this->callRequest->GetAllMessage();

        return $this->render('main/message.html.twig', [
            'all_message' => $messageAllList,
        ]);
    }

    #[Route('/buy', name: 'buy')]
    public function buy(): Response
    {
        return $this->render('main/buy.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route(path: '/favorite', name: 'favorite')]
    public function favorite(): Response
    {
        return $this->render('main/favorite.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route(path: '/buyed', name: 'buyed')]
    public function buyed(): Response
    {
        $buyedArticle = $this->callRequest->GetBuyedArticleFromUser();

        return $this->render('main/buyed.html.twig', [
            'selledArticle' => $buyedArticle
        ]);
    }

    #[Route(path: '/selled', name: 'selled')]
    public function selled(): Response
    {
        $selledArticle = $this->callRequest->GetSelledArticleFromUser();

        return $this->render('main/selled.html.twig', [
            'selledArticle' => $selledArticle
        ]);
    }
}
