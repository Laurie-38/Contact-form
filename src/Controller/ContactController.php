<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Requester;
use App\Form\ContactFormType;
use App\Repository\RequesterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, RequesterRepository $requesterRepository, ParameterBagInterface $parameterBag, TranslatorInterface $translator): Response
    {
		$message = new Message();
		$form = $this->createForm(ContactFormType::class);
	    $form->handleRequest($request);
		$data = $form->getData();

		if($form->isSubmitted() && $form->isValid()){
			$message->setBody($data['request']);
			$message->setSubject($data['subject']);

			// Si le requester existe, ajout du message au requester:
			$requesterOnEmail = $requesterRepository->findOneBy(['email' => $data['email']]);
			if($requesterOnEmail) {
				$requester = $requesterOnEmail;
				$requester->addMessage($message);
			}
			// Sinon => création nouveau requester
			else {
				$requester = new Requester();
				$requester->setName($data['name']);
				$requester->setEmail($data['email']);
			}

			//Enregistrement BDD
			$message->setRequester($requester);
			$entityManager->persist($message);
			$entityManager->persist($requester);
			$entityManager->flush();
			$this->addFlash('success', $translator->trans('flashes.success'));

			//Enregistrement JSON
			$filesystem = new Filesystem();

			//Création dossier
			if(!$filesystem->exists('../Contacts')) {
				$filesystem->mkdir('../Contacts');
			}
			//Création fichier JSON
			$nameFile = md5($message->getId().$requester->getId()).'.json';
			$newFile = '../Contacts/'.$nameFile;
			$filesystem->touch($newFile);

			//prépare la data et ajout au JSON
			$jsonDdata = json_encode($data);
			$filesystem->appendToFile($newFile, $jsonDdata);

			// Si pb lors de l envoi du form :
		} elseif ($form->isSubmitted()) {
			$this->addFlash('danger', $translator->trans('flashes.alert'));
		}
        return $this->render('contact/index.html.twig', [
	        'form' => $form->createView(),
        ]);
    }
}
