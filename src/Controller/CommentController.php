<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/list/comment", name="list_comment")
     *
     * @param CommentRepository $commentRepository
     * @return void
     */
    public function home(CommentRepository $commentRepository): Response
    {
        $commentList = $commentRepository->findAll();
        $comments = $commentRepository->findBy([], ['username' => 'ASC']);
        return $this->render('comment/home.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/add/comment/{id}", name="add_comment", methods={"POST","GET"}, requirements={"id"="\d+"})
     * 
     * @param ManagerRegistry $doctrine
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function add(ManagerRegistry $doctrine, Request $request, Post $post): Response
    {
        $newComment = new Comment();
        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newComment->setPost($post);
            $newComment->setCreatedAt(new DateTimeImmutable());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($newComment);
            $entityManager->flush();

            return $this->redirectToRoute('show', ['id' => $newComment->getPost()->getId()]);
        }
        return $this->render('comment/add.html.twig',[
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/update/comment/{id}", name="update_comment", methods={"POST","GET"}, requirements={"id"="\d+"})
    *
    * @param Comment $comment
    * @param CommentRepository $commentRepository
    * @param Request $request
    * @return Response
    */
    public function updatePost(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {          
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setUpdatedAt(new DateTimeImmutable());
            $commentRepository->add($comment, true);
            
            return $this->redirectToRoute('list_comment');
        } 
        return $this->render('comment/update.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
    

    /**
     * @Route("/delete/comment/{id}", name="delete_comment")
     *
     * @param [type] $id
     * @param ManagerRegistry $doctrine
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function delete($id, ManagerRegistry $doctrine, CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->find($id);
        
        $entityManager = $doctrine->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('list_comment');
    }
}
