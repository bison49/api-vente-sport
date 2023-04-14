<?php
namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app-login", methods={"POST"})
     */
    public function login(Request $request, UserRepository $userRepository): JsonResponse
    {

        $user = $this->getUser();

        return $this->json(['user'=> $user]);

    }

    /**
     * @Route("/api/me", name="app-me", methods={"GET"}, format="json")
     */
    public function me(): JsonResponse
    {
       $user = $this->getUser();

       if($user) {
           if (!$user->getUserInfo()->isIsActive()) {
               throw new HttpException(401, "Vous n'avez pas activé votre compte");
           }

           return $this->json([
               'id' => $user->getId(), 'email' => $user->getEmail(), 'role' => $user->getRoles(), 'username' => $user->getUsername(), 'userInfo' => $user->getUserInfo(),
           ]);
       } else {
           throw new NotFoundHttpException("Cet utilisateur est introuvable");
       }
    }
}