<?php

namespace App\Service;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;


class CallRequest

{
    public function __construct(private LoggerInterface $logger ,private readonly EntityManagerInterface
    $entityManager) {
       
    }
    public function GetAllArticle ()
    {
        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 0]);
        
        return $allArticle;
    }

    public function GetAllArticleByCategory (string $category)
    {
        $articleList = $this->entityManager->getRepository(Article::class);
        $allArticle = $articleList->findBy(['status' => 0, "category" => $category]);
        
        return $allArticle;
    }

    public function GetAllUser ()
    {
        $userList = $this->entityManager->getRepository(User::class);
        $allUser = $userList->findAll();

        return $allUser;
    }
}
