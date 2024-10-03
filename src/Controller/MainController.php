<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Message;
use App\Form\SendMessageType;
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
        return $this->render('main/profile.html.twig', [
            'controller_name' => 'MainController',
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
    public function message(Request $request, EntityManagerInterface $entityManager): Response
    {
        $messageAllList = $this->callRequest->GetAllMessage();

        $message = new Message();
        $form = $this->createForm(SendMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $message->setReceiver($this->getUser());

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'You can view your article or add another article');
        }

        return $this->render('main/message.html.twig', [
            'all_message' => $messageAllList,
            'send_message' => $form,
        ]);
    }

    #[Route('/message/{receiver}', name: 'messageCategory')]
    public function messageCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $messageAllList = $this->callRequest->GetAllMessage();

        $message = new Message();
        $form = $this->createForm(SendMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $message->setReceiver($this->getUser());

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'You can view your article or add another article');
        }

        return $this->render('main/message.html.twig', [
            'all_message' => $messageAllList,
            'send_message' => $form,
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
}
