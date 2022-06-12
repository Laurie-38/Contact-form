<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration')]
    public function register(Request                    $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             UserAuthenticatorInterface $userAuthenticator,
                             EntityManagerInterface     $entityManager,
                             Authenticator $authenticator): Response
    {
	    $user = new User();
	    $form = $this->createForm(RegistrationType::class, $user);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		    // encode the plain password
		    $user->setPassword(
			    $userPasswordHasher->hashPassword(
				    $user,
				    $form->get('password')->getData()
			    )
		    );

			//Décommenter pour attribuer le role ADMIN au premier utilisateur
			//$user->setRoles(["ROLE_ADMIN"]);

		    $entityManager->persist($user);
		    $entityManager->flush();

		    return $userAuthenticator->authenticateUser(
			    $user,
			    $authenticator,
			    $request,
		    );
		}

	    return $this->render('registration/index.html.twig', [
		    'form' => $form->createView(),
	    ]);
    }
}
