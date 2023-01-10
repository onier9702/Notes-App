<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $encryptPassword
    ): Response
    {

        $em = $doctrine->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $hashedPassword = $encryptPassword->hashPassword(
                $user,
                $form['password']->getData()
            );
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
            $this->addFlash( 'success', 'You have signed up successfully' );
            return $this->redirectToRoute('app_dashboard');

        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form'=>$form->createView()
        ]);
    }
}
