<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NoteCreateType;
use App\Form\NoteEditType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    #[Route('/create-note', name: 'app_create_notes')]
    public function createNote(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        $em = $doctrine->getManager();
        $note = new Notes();
        $form = $this->createForm(NoteCreateType::class, $note);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            $user = $this->getUser();
            $note->setUser($user);

            $em->persist($note);
            $em->flush();
            return $this->redirectToRoute('app_dashboard');

        }

        return $this->render('notes/index.html.twig', [
            // 'controller_name' => 'NotesController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/note/{id}', name: 'edit_note')]
    public function seeOneNoteByID($id, Request $request, ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $note = $em->getRepository(Notes::class)->find($id);
        $form = $this->createForm(NoteEditType::class, $note);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {            

            $em->persist($note);
            $em->flush();
            return $this->redirectToRoute('app_dashboard');

        }

        return $this->render('notes/seeNote.html.twig', [
            // 'controller_name' => 'NotesController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete_note/{id}', name: 'delete_note')]
    public function deleteOneNoteById($id, Request $request, ManagerRegistry $doctrine ) {
        $em = $doctrine->getManager();
        $note = $em->getRepository(Notes::class)->find($id);
        $note = $note->setIsActive(false);
        // $note = $note->setDateRemoved();

        $em->persist($note);
        $em->flush();

        return $this->render('notes/deletedNote.html.twig', [
            'noteDeleted' => $note
        ]);

    }

    #[Route('/restore_note/{id}', name: 'restore_note')]
    public function restoreNoteById($id, ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $note = $em->getRepository(Notes::class)->find($id);
        $note = $note->setIsActive(true);

        $em->persist($note);
        $em->flush();

        return $this->render('notes/restoredNote.html.twig', [
            'noteRestored' => $note
        ]);
    }

}
