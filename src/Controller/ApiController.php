<?php

namespace App\Controller;

use App\Service\CallRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ApiController extends AbstractController
{
    #[Route('/add-favorite', name: 'add_favorite')]
    public function addFavorite(Request $request, CallRequest $callRequest)
    {
        $articleId = $request->query->get('article_id');

        $article = $callRequest->GetArticle($articleId);

        if ($this->getUser() == $article->getSeller()) {
            return $this->redirectToRoute('article', [
                'id' => $articleId
            ]);
        }

        $callRequest->SwitchFavorite($article);

        return $this->redirectToRoute('article', [
            'id' => $articleId
        ]);
    }
}
