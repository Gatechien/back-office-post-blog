<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/post", name="api_posts_")
 */
class PostController extends ApiController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function browse(PostRepository $postRepository): JsonResponse
    {
        $posts = $postRepository->findAll();

        return $this->json(
            $posts,
            Response::HTTP_OK,
            [],
            ["groups" =>[
                "app_api_post_browse"
            ]]
        );
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Post $post = null): JsonResponse
    {
        if ($post === null) {
            return $this->json404();
        }
        return $this->json200(
            $post,
            ["groups" => [
                "app_api_post"
            ]]
        );
    }

    /**
     * @Route("",name="add", methods={"POST"})
     *
     * @param Request $request
     * @param ManagerRegistry $manager
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function add(Request $request, ManagerRegistry $manager, SerializerInterface $serializerInterface, ValidatorInterface $validator): JsonResponse
    {
        if (!$this->isGranted("ROLE_USER")) {
            return $this->json(["error"=>"Authorised user only"], Response::HTTP_FORBIDDEN);
        }

        $jsonContent = $request->getContent();

        try {
            $newPost = $serializerInterface->deserialize($jsonContent, Post::class, 'json');
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($newPost);

        if (count($errors)> 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $newPost->setCreatedAt(new DateTimeImmutable());
        $newPost->setPublishedAt(new DateTimeImmutable());
        $em = $manager->getManager();
        $em->persist($newPost);
        $em->flush();

        return $this->json(
            $newPost,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_posts_read', ['id' => $newPost->getId()])
            ],
            [
                "groups" => "app_api_post"
            ]
        );
    }

    /**
     * @Route("/{id}",name="edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(Post $post = null, Request $request, ManagerRegistry $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        if ($post === null) {
            return $this->json404();
        }

        $jsonContent = $request->getContent();

        $serializerInterface->deserialize($jsonContent, Author::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $post]);

        $post->setUpdatedAt(new DateTimeImmutable());
        $doctrine->getManager()->flush();

        return $this->json(
            $post,
            Response::HTTP_PARTIAL_CONTENT,
            [
                'Location' => $this->generateUrl('api_posts_read', ['id' => $post->getId()])
            ],
            [
                "groups" => "app_api_post"
            ]
        );
    }

    /**
     * @Route("/{id}",name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @param PostRepository $postRepository
     * @param Post $post
     */
    public function delete(Post $post = null, PostRepository $postRepository)
    {
        if ($post === null) {
            return $this->json404();
        }

        $postRepository->remove($post, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                'Location' => $this->generateUrl('api_posts_browse')
            ]
        );
    }
}