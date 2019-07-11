<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Swift_Image;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{

    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository): Response
    {
        $user  = $this->getUser();
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findBy([
                'destinataire'=>$user
            ])
        ]);
    }

    /**
     * @Route("/new/{id<[0-9]+>}", name="message_new", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $entityManager
     * @param Annonces $annonce
     * @return Response
     */
    public function new(Request $request, ObjectManager $entityManager,Annonces $annonce): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAnnonce($annonce);
            $message->setDestinataire($annonce->getAuthor());
            $message->setAuthor($this->getUser());
            $this->sendMailMessage($message);
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('message_index');
        }
        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new/reply/{slug<[a-z0-9\_-]+>}/{id}", name="message_reply", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $entityManager
     * @param Annonces $annonce
     * @param Message $oldMessage
     * @return Response
     */
    public function reply(Request $request, ObjectManager $entityManager,Annonces $annonce, Message $oldMessage): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAnnonce($annonce);
            $message->setDestinataire($oldMessage->getAuthor());
            $message->setAuthor($this->getUser());
            $this->sendMailMessage($message);
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('message_index');
        }
        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/lire/{id}", name="message_show", methods={"GET"})
     */
    public function show(Message $message, ObjectManager $entityManager): Response
    {
        $message->setIsRead(true);
        $entityManager->flush();
        return $this->render('message/show.html.twig', [
            'message' => $message,

        ]);
    }
    /**
     * @Route("/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }

    public function getNumberMessage(MessageRepository $message):Response
    {
        $user = $this->getUser();
        $n = $message->createQueryBuilder('mess');
        $n->select('count(mess.destinataire)');
        $nbreMessage = $n
            ->where('mess.destinataire = :user')
            ->andWhere('mess.isRead = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 0)
            ->getQuery()
            ->getSingleScalarResult();
        return $this->render(
            'inc/numberMessage.html.twig',
            ['nbreMessage' => $nbreMessage]);
    }

    /**
     * @param Message $message
     * @param Swift_Mailer $mailer
     */
    public function sendMailMessage(Message $message):void
    {
        $mail = (new Swift_Message('broc.com : Vous avez un nouveau message'))
            ->setContentType("text/html")
            ->setFrom('tibdoranco@gmail.com')
            ->setTo($message->getDestinataire()->getUserMail())
        ;
        $img = $mail->embed(Swift_Image::fromPath('img/divers/logo_black.svg'));
        $mail->setBody(
            $this->renderView(
                'message.html.twig',
                [
                    'messageTitre' => $message->getMessageTitre(),
                    'messageTexte' => $message->getMessageTexte(),
                    'messageAnnonce'=> $message->getAnnonce()->getAnnonceTitre(),
                    'img' => $img,
                ]
            )
        );
        $this->mailer->send($mail);
    }
}
