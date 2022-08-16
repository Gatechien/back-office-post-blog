<?php

namespace App\Controller\Back;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/list/author", name="list_author")
     *
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function home(AuthorRepository $authorRepository): Response
    {
        $authorList = $authorRepository->findAll();
        $authors = $authorRepository->findBy([], ['firstname' => 'ASC']);
        return $this->render('author/home.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/add/author", name="add_author", methods={"POST","GET"})
     *
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function add (ManagerRegistry $doctrine, Request $request): Response
    {
        $newAuthor = new Author();
        $form = $this->createForm(AuthorType::class, $newAuthor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $newAuthor->setCreatedAt(new DateTimeImmutable());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($newAuthor);
            $entityManager->flush();

            return $this->redirectToRoute('list_author');
        } else {
            return $this->render('author/add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }   

    /**
    * @Route("/update/author/{id}", name="update_author", methods={"POST","GET"}, requirements={"id"="\d+"})
    *
    * @param AuthorRepository $authorRepository
    * @param Author $author
    * @param Request $request
    * @return void
    */
    public function updateAuthor(Request $request, Author $author, AuthorRepository $authorRepository ): Response
    {   
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author->setUpdatedAt(new DateTimeImmutable());
            $authorRepository->add($author, true);

            return $this->redirectToRoute('list_author');
        }
        return $this->render('author/update.html.twig', [
            'author' => $author,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/author/{id}", name="delete_author")
     *
     * @param [type] $id
     * @param ManagerRegistry $doctrine
     * @param AuthorRepository $authoRepository
     * @return Response
     */
    public function delete($id, AuthorRepository $authorRepository, ManagerRegistry $doctrine): Response
    {
        $author = $authorRepository->find($id);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('list_author');
    }
}

