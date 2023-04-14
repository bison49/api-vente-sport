<?php
namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/api/add-article", name="app-add-article", methods={"POST"})
     */
    public function addArticle(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        return $this->json(['response' => "c'est bon"]);
    }
}