<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CallRequest
{
    public function __construct(
        private LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    public function GetAllArticle()
    {
        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findAll();

        return $allArticle;
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
