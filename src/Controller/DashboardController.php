<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
// use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function dashboard(
        ManagerRegistry $doctrine,
        Request $request,
        // LoggerInterface $logger,
    ): Response
    {

        $em = $doctrine->getManager();
        $user = $this->getUser();
        $notesBelongUser = $em->getRepository(Notes::class)->findBy(['user' => $user]);
        $notesArePublic = $em->getRepository(Notes::class)->findAllNotesArePublic(true, $user);
        // $notes = $em->getRepository(Notes::class)->findAllNotesArePublic(true, $user);
        $notes = array_merge($notesBelongUser, $notesArePublic);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'notes' => $notes
        ]);
    }
}
