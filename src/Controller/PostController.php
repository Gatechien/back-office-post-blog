<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * @param PostRepository $postRepository
     * @return Response
     */
    public function home(PostRepository $postRepository): Response
    {
        $postList = $postRepository->findAll();
        return $this->render('post/home.html.twig', [
            'postList' => $postList,
        ]);
    }

    /**
    * @Route("/post/{id}", name="show", methods={"POST","GET"}, requirements={"id"="\d+"})
    *
    * @param [type] $id
    * @param ManagerRegistry $doctrine
    * @param Request $request
    * @return Response
    */
    public function show($id, Request $request, ManagerRegistry $doctrine): Response
    {
        $postRepository = $doctrine->getRepository(Post::class);
        $post = $postRepository->find($id);
        
        if ($request->isMethod('POST')) {

            $post = $postRepository->find($id);
            $likes = $post->getNbLikes();

            if ($like = $request->request->get('NbLikesUp')) {
                $post->setNbLikes($likes + $like);
            } else {
                $like = $request->request->get('NbLikesDown');
                $post->setNbLikes($likes - $like);
            }
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('home');

        } else {
            return $this->render('post/show.html.twig', [
                'post' => $post            
            ]);
        }
    }

    /**
     * @Route("/post/add", name="add", methods={"POST","GET"})
     * 
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function add (ManagerRegistry $doctrine, Request $request): Response
    {    
        $newPost = new Post();
        $form = $this->createForm(PostType::class, $newPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newPost->setCreatedAt(new DateTimeImmutable());
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($newPost);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        } else {
            return $this->renderForm('post/add.html.twig', [
                'form' => $form
            ]);
        }
    }

    /**
    * @Route("/post/update/{id}", name="update", methods={"GET","POST"}, requirements={"id"="\d+"})
    *
    * @param PostRepository $postRepository
    * @param Post $post
    * @param Request $request
    * @return Response
    */
    public function updatePost(Request $request, Post $post, PostRepository $postRepository): Response
    {        
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $post->setUpdatedAt(new DateTimeImmutable());
            $postRepository->add($post, true);
            
            return $this->redirectToRoute('home');
        }
        return $this->render('post/update.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
            
    /**
     * @Route("/post/delete/{id}", name="delete")
     *
     * @param [type] $id
     * @param ManagerRegistry $doctrine
     * @param PostRepository $postRepository
     * @return Response
     */
    public function delete($id, ManagerRegistry $doctrine, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);
        
        $entityManager = $doctrine->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}
