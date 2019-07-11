<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Swift_Mailer;
use Swift_Message;
use Swift_Image;
use App\Form\RegistrationFormType;
use App\Security\AuthAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{


    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AuthAuthenticator $authenticator
     * @param ObjectManager $entityManager
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        AuthAuthenticator $authenticator,
        ObjectManager $entityManager,
        Swift_Mailer $mailer
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $message = (new Swift_Message('Bienvenue sur broc.com'))
                ->setContentType("text/html")
                ->setFrom('tibdoranco@gmail.com')
                ->setTo($user->getUserMail())
            ;
            $img = $message->embed(Swift_Image::fromPath('img/divers/logo_black.svg'));
            $message->setBody(
                $this->renderView(
                    'hello.html.twig',
                    [
                        'name' => $user->getUserMail(),
                        'id'=>$user->getId(),
                        'img' => $img,
                        'token'=>$user->getToken()
                    ]
                )
            );
            $mailer->send($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in user.yaml
            );
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation/{id}/{token}", name="app_validCompte")
     * @param User $user
     * @param string $token
     * @param ObjectManager $entityManager
     * @return Response
     */
    public function validCompte(
        User $user,
        string $token,
        ObjectManager $entityManager
    ): Response
    {
        if ($user->getToken() === $token){
            $user->setIsValid(1);
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte a bien été validé, bienvenue!');
        }
        return $this->redirectToRoute('index');
    }
}
