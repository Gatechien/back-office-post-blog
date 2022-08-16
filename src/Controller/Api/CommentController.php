<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
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
 * @Route("/api/comment", name="api_comments_")
 */
class CommentController extends ApiController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function browse(CommentRepository $commentRepository): JsonResponse
    {
        $comments = $commentRepository->findAll();

        return $this->json(
            $comments,
            Response::HTTP_OK,
            [],
            ["groups" =>[
                "app_api_comment_browse"
            ]]
        );
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Comment $comment = null): JsonResponse
    {
        if ($comment === null) {
            return $this->json404();
        }
        return $this->json200(
            $comment,
            ["groups" => [
                "app_api_comment"
            ]]
        );
    }

    /**
     * @Route("/{id}",name="add", methods={"POST"})
     *
     * @param Request $request
     * @param ManagerRegistry $manager
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function add(Post $post, Request $request, ManagerRegistry $manager, SerializerInterface $serializerInterface, ValidatorInterface $validator): JsonResponse
    {
        if (!$this->isGranted("ROLE_USER")) {
            return $this->json(["error"=>"Authorised user only"], Response::HTTP_FORBIDDEN);
        }

        $jsonContent = $request->getContent();

        try {
            $newComment = $serializerInterface->deserialize($jsonContent, Comment::class, 'json');
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($newComment);

        if (count($errors)> 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $newComment->setPost($post);
        $newComment->setCreatedAt(new DateTimeImmutable());
        $newComment->setPublishedAt(new DateTimeImmutable());

        $em = $manager->getManager();
        $em->persist($newComment);
        $em->flush();

        return $this->json(
            $newComment,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_comments_read', ['id' => $newComment->getId()])
            ],
            [
                "groups" => "app_api_comment"
            ]
        );
    }

    /**
     * @Route("/{id}",name="edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(Comment $comment = null, Request $request, ManagerRegistry $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        if ($comment === null) {
            return $this->json404();
        }

        $jsonContent = $request->getContent();

        $serializerInterface->deserialize($jsonContent, Author::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $comment]);

        $comment->setUpdatedAt(new DateTimeImmutable());
        $doctrine->getManager()->flush();

        return $this->json(
            $comment,
            Response::HTTP_PARTIAL_CONTENT,
            [
                'Location' => $this->generateUrl('api_comments_read', ['id' => $comment->getId()])
            ],
            [
                "groups" => "app_api_comment"
            ]
        );
    }

    /**
     * @Route("/{id}",name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @param CommentRepository $commentRepository
     * @param Comment $comment
     */
    public function delete(Comment $comment = null, CommentRepository $commentRepository)
    {
        if ($comment === null) {
            return $this->json404();
        }

        $commentRepository->remove($comment, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                'Location' => $this->generateUrl('api_comments_browse')
            ]
        );
    }
}
