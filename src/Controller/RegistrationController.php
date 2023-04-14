<?php
namespace App\Controller;

use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\SendMailService;
use App\Service\JWTService;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/sendMail", name="app-send-mail", methods={"POST"})
     * @throws TransportExceptionInterface
     */
    public function sendMail(Request $request, SendMailService $mail, UserRepository $userRepository, JWTService $jwt, UserInfoRepository $userInfoRepository): Response
    {

        $response = $request->getContent();
        $content = json_decode($response);
        $email = $content->{'email'};

        $user = $userRepository->findOneBy(["email" => $email]);

        $firstname = strval($user->getUserInfo()->getFistname());

        // On génère le JWT de l'utilisateur
        // On crée le Header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // On crée le Payload
        $payload = [
            'user_id' => $user->getId()
        ];

        // On génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        try {
            $mail->send(
                'no-reply@sposirte.net',
                $user->getEmail(),
                'Activation de votre compte sur le site e-commerce',
                'register',
                compact('firstname', 'token')
            );
            return $this->json([
                'success' => $token
            ]);
        } catch (HttpException){
            throw new HttpException(401, "Le mail n'a pas pu être envoyé");
        }
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository, UserInfoRepository $userInfoRepository): RedirectResponse
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $userRepository->find($payload['user_id']);

            $userInfo = $userInfoRepository->find($user->getUserInfo()->getId());

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->getUserInfo()->isIsActive()){
                $user->getUserInfo()->setIsActive(true);
                $userInfoRepository->save($userInfo,true);
                return $this->redirect('http://localhost:3000/login/activated');
            }
        }
        // Ici un problème se pose dans le token
        throw new HttpException(401, "Le token est invalide ou a expiré");
    }
}