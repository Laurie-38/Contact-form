<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\RequesterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(MessageRepository $messageRepository, RequesterRepository $requesterRepository): Response
    {
		$requesters = $requesterRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
	        'requesters' => $requesters
        ]);
    }

	#[Route('/processed/{id}', name: 'admin_processed')]
	public function processedMessage($id, MessageRepository $messageRepository, EntityManagerInterface $entityManager): Response
	{
		$message = $messageRepository->find($id);

		$message->setIsProcessed(true);
		$entityManager->persist($message);
		$entityManager->flush();

		return $this->redirectToRoute('app_admin');
	}
}
