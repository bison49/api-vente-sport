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


class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/api/resetpasswordmail", name="app-reset-password-email", methods={"POST"})
     * @throws TransportExceptionInterface
     */
    public function resetPasswordEmail(Request $request, SendMailService $mail, UserRepository $userRepository, UserInfoRepository $userInfoRepository): Response
    {

        $response = $request->getContent();
        $content = json_decode($response);
        $email = $content->{'email'};

        $user = $userRepository->findOneBy(["email" => $email]);

        if (!$user){
            throw new HttpException(404, "Aucun utilisateur avec cet email n'a été trouvé.");
        }

        $firstname = strval($user->getUserInfo()->getFistname());

        $ActivationCode = mt_rand(100000,999999); 

        try {
            $mail->send(
                'no-reply@sposirte.net',
                $user->getEmail(),
                'Votre code permettant de modifier votre mot de passe',
                'mail_reset',
                compact('firstname', 'ActivationCode')
            );
            return $this->json([
                'success' => $ActivationCode, 'id' => $user->getId()
            ]);
        } catch (HttpException){
            throw new HttpException(401, "Le mail n'a pas pu être envoyé");
        }
    }
    
}