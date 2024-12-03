<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/signup', name: 'app_signup')]
    public function signup(AuthenticationUtils $authenticationUtils , Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response{

        $duck = new duck();
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $passwordHasher->hashPassword($duck, $duck->getPassword());
            $duck->setPassword($hash);
            $em->persist($duck);
            $em->flush();
        }

        return $this->render('security/signup.html.twig', [
            'form1' => $form->createView(),

        ]);
    }

    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
