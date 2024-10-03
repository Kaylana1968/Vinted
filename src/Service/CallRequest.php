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

    public function GetAllUser()
    {
        $userList = $this->entityManager->getRepository(User::class);
        $allUser = $userList->findAll();

        return $allUser;
    }

    public function GetAllMessagedUser()
    {
        $user = $this->security->getUser();

        $messagedUser = [];
        $messageList = $this->entityManager->getRepository(Message::class);

        $messageSenderList = $messageList->findBy(['sender' => $user]);
        foreach ($messageSenderList as $message) {
            array_push($messagedUser, $message->getReceiver());
        }

        $messageReceiverList = $messageList->findBy(['receiver' => $user]);
        foreach ($messageReceiverList as $message) {
            array_push($messagedUser, $message->getSender());
        }

        return array_unique($messagedUser);
    }

    public function GetAllMessage()
    {
        $user = $this->security->getUser();

        $message = $this->entityManager->getRepository(Message::class);
        $messageSenderList = $message->findBy(['sender' => $user]);
        $messageReceiverList = $message->findBy(['receiver' => $user]);
        $messageAllList = array_merge($messageSenderList, $messageReceiverList);

        return $messageAllList;
    }
}
