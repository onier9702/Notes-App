<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\TagsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    #[Route('/tags', name: 'app_tags')]
    public function createTag( Request $request, ManagerRegistry $doctrine ): Response
    {

        $em = $doctrine->getManager();
        $tag = new Tags();
        $formTag = $this->createForm(TagsType::class, $tag);
        $formTag->handleRequest($request);
        // $tags = $em->getRepository(Tags::class)->getAllTags();
        // $tags = $em->getRepository(Tags::class)->findAll();

        if ( $formTag->isSubmitted() && $formTag->isValid() ) {

            $em->persist($tag);
            $em->flush();
            return $this->redirectToRoute('app_dashboard');

        }

        return $this->render('tags/index.html.twig', [
            'formTag' => $formTag,
            // 'tags' => $tags
        ]);
    }
}
