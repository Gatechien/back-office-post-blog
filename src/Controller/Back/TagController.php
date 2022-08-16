<?php

namespace App\Controller\Back;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/list/tag", name="list_tag")
     *
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function home(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findBy([], ['name' => 'ASC']);
        return $this->render('tag/home.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/add/tag", name="add_tag", methods={"POST","GET"})
     *
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function add (ManagerRegistry $doctrine, Request $request): Response
    {
        $newTag = new Tag();
        $form = $this->createForm(TagType::class, $newTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            $entityManager->persist($newTag);
            $entityManager->flush();

            return $this->redirectToRoute('list_tag');
        } else {
            return $this->render('tag/add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
    
    /**
    * @Route("/update/tag/{id}", name="update_tag", methods={"POST","GET"}, requirements={"id"="\d+"})
    *
    * @param TagRepository $tagRepository
    * @param Tag $tag
    * @param Request $request
    * @return void
    */
    public function updateTag(Request $request, Tag $tag ,TagRepository $tagRepository): Response
    {   
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tagRepository->add($tag, true);

            return $this->redirectToRoute('list_tag');
        }
        return $this->render('tag/update.html.twig', [
            'tag' => $tag,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/tag/{id}", name="delete_tag")
     *
     * @param [type] $id
     * @param ManagerRegistry $doctrine
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function delete($id, TagRepository $tagRepository, ManagerRegistry $doctrine): Response
    {
        $tag = $tagRepository->find($id);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($tag);
        $entityManager->flush();

        return $this->redirectToRoute('list_tag');
    }
}
