<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Entity\Tags;
use App\Form\NoteCreateType;
use App\Form\NoteEditType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{

    protected $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    // Create Note
    #[Route('/create-note', name: 'app_create_notes')]
    public function createNote(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        $em = $doctrine->getManager();
        $note = new Notes();
        $tags = $em->getRepository(Tags::class)->getAllTags();
        $form = $this->createForm(NoteCreateType::class, $note, [
            'tag' => $tags,
        ]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            $tag = $form->get('tag')->getData();
            $note->setTag($tag);
            $user = $this->getUser();
            $note->setUser($user);

            $em->persist($note);
            $em->flush();
            return $this->redirectToRoute('app_dashboard');

        }

        return $this->render('notes/index.html.twig', [
            'form' => $form->createView(),
            'tags' => $tags,
        ]);
    }

    // Edit Notes
    #[Route('/note/{id}', name: 'edit_note')]
    public function seeOneNoteByID($id, Request $request, ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $note = $em->getRepository(Notes::class)->find($id);
        $tags = $em->getRepository(Tags::class)->getAllTags();
        $form = $this->createForm(NoteEditType::class, $note, [
            'tag' => $tags,
        ]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {            

            $tag = $form->get('tag')->getData();
            $note->setTag($tag);
            
            $em->persist($note);
            $em->flush();
            return $this->redirectToRoute('app_dashboard');

        }

        return $this->render('notes/seeNote.html.twig', [
            // 'controller_name' => 'NotesController',
            'form' => $form->createView(),
            'tags' => $tags,
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
