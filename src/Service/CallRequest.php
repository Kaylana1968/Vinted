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
        $favoriteList = $this->entityManager->getRepository(Favorite::class);
        $allFavorite = $favoriteList->findBy(['status' => 0]);

        return $allFavorite;
    }

    public function getUser($userId)
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

    public function GetSelledArticleFromUser ()
    {
        $user = $this->security->getUser();

        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 1, "seller" => $user]);
        
        return $allArticle;
    }

    public function GetBuyedArticleFromUser ()
    {
        $user = $this->security->getUser();

        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 1, "buyer" => $user]);
        
        return $allArticle;
    }
}
