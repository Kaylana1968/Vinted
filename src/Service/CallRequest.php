<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Favorite;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CallRequest
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    public function GetArticle(int $articleId)
    {
        $articleList = $this->entityManager->getRepository(Article::class);
        $article = $articleList->findOneBy(['id' => $articleId]);

        return $article;
    }

    public function GetAllArticle()
    {
        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 0]);

        return $allArticle;
    }

    public function GetAllArticleByCategory(string $category)
    {
        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 0, "category" => $category]);

        return $allArticle;
    }

    public function GetAllFavoris()
    {
        $user = $this->security->getUser();

        $favoriteList = $this->entityManager->getRepository(Favorite::class);
        $allFavorite = $favoriteList->findBy(["user" => $user]);

        return $allFavorite;
    }

    public function GetUser($userId)
    {
        $userList = $this->entityManager->getRepository(User::class);
        $receiver = $userList->findOneBy(['id' => $userId]);

        return $receiver;
    }

    public function GetAllUser()
    {
        $userList = $this->entityManager->getRepository(User::class);
        $allUser = $userList->findAll();

        return $allUser;
    }

    public function GetAllMessagedUser()
    {
        $user = $this->security->getUser(); // connected user

        $messagedUser = array();

        $messageList = $this->entityManager->getRepository(Message::class);

        $messageSenderList = $messageList->findBy(['sender' => $user]);
        foreach ($messageSenderList as $message) {
            $receiver = $message->getReceiver();
            if (!in_array($receiver, $messagedUser)) {
                array_push($messagedUser, $receiver);
            }
        }

        $messageReceiverList = $messageList->findBy(['receiver' => $user]);
        foreach ($messageReceiverList as $message) {
            $sender = $message->getSender();
            if (!in_array($sender, $messagedUser)) {
                array_push($messagedUser, $sender);
            }
        }

        return $messagedUser;
    }

    public function GetMessage($receiver)
    {
        $user = $this->security->getUser(); // connected user

        $messageList = $this->entityManager->getRepository(Message::class);
        $messageSenderList = $messageList->findBy(['sender' => $user, 'receiver' => $receiver]);
        $messageReceiverList = $messageList->findBy(['receiver' => $user, 'sender' => $receiver]);

        return array_merge($messageSenderList, $messageReceiverList);
    }

    public function GetSelledArticleFromUser()
    {
        $user = $this->security->getUser();

        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 1, "seller" => $user]);

        return $allArticle;
    }

    public function GetBuyedArticleFromUser()
    {
        $user = $this->security->getUser();

        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 1, "buyer" => $user]);

        return $allArticle;
    }

    public function SwitchFavorite(Article $article)
    {
        $user = $this->security->getUser();

        $favoriteList = $this->entityManager->getRepository(Favorite::class);
        if ($favorite = $favoriteList->findOneBy([
            'user' => $user,
            'article' => $article
        ])) { // if already favorited
            $this->entityManager->remove($favorite);
        } else {
            $favorite = new Favorite();

            $favorite->setUser($user);
            $favorite->setArticle($article);

            $message = new Message();
            $message->setSender($user);
            $message->setReceiver($article->getSeller());
            $message->setContent(
                'User ' .
                $user->getName() .
                ' has added to favorites your product ' .
                $article->getTitle() .
                '.'
            );

            $this->entityManager->persist($favorite);
            $this->entityManager->persist($message);
        }

        $this->entityManager->flush();
    }

    public function GetSoldArticle()
    {
        $user = $this->security->getUser();

        $articleList = $this->entityManager->getRepository(Article::class);
        $soldArticle = $articleList->findBy(["seller" => $user, "status" => 1]);

        return $soldArticle;
    }

    public function GetReceipts(array $soldArticle)
    {
        $sum = 0;
        foreach ($soldArticle as $article) {
            $sum += $article->getPrice();
        }

        return $sum;
    }
}
