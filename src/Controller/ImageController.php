<?php
namespace App\Controller;


use App\Repository\ArticleRepository;
use App\Entity\ImageArticle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("api/add-images", name="app-add-images", methods={"POST"})
     */
    public function addImage(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        $images = $_FILES;

        $article = $articleRepository->find($_POST['articleId']);
        
        foreach ($images as $image) {

            $file = new UploadedFile($image["tmp_name"], $image["name"], $image["type"], $image["error"]);

            // On génère un nouveau nom de fichier
            $name = md5(uniqid()) . '.' . $file->guessExtension();

            // On copie le fichier dans le dossier uploads
            $file->move($this->getParameter('articles_imgs_dir'), $name);

            $img = new ImageArticle();
            $img->setUri($name);
            $article->addImage($img);
        }
        $articleRepository->save($article, true);
        return $this->json([
            'message' => "Votre article a bien été créé, et est en attente de validation par un modérateur"
        ]);
    }
}