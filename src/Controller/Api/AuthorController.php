<?php

namespace App\Controller\Api;

use App\Entity\Author;
use App\Repository\AuthorRepository;
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
 * @Route("/api/author", name="api_authors_")
 */
class AuthorController extends ApiController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function browse(AuthorRepository $authorRepository): JsonResponse
    {
        $authors = $authorRepository->findAll();

        return $this->json(
            $authors,
            Response::HTTP_OK,
            [],
            ["groups" =>[
                "app_api_author_browse"
            ]]
        );
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Author $author = null): JsonResponse
    {
        if($author === null)
        {
            return $this->json404();
        }
        return $this->json200(
            $author,
            ["groups" => [
                "app_api_author"
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
        if (!$this->isGranted("ROLE_USER"))
        {
            return $this->json(["error"=>"Authorised user only"], Response::HTTP_FORBIDDEN);
        }

        $jsonContent = $request->getContent();

        try 
        { 
            $newAuthor = $serializerInterface->deserialize($jsonContent, Author::class, 'json');
        }
        catch(Exception $e) 
        {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($newAuthor);
        
        if (count($errors)> 0)
        {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $newAuthor->setCreatedAt(new DateTimeImmutable());
        $em = $manager->getManager();
        $em->persist($newAuthor);
        $em->flush();

        return $this->json(
            $newAuthor,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_authors_read', ['id' => $newAuthor->getId()])
            ],
            [
                "groups" => "app_api_author"
            ]
        );
    }

    /**
     * @Route("/{id}",name="edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(Author $author = null, Request $request, ManagerRegistry $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        if ($author === null){ return $this->json404(); }

        $jsonContent = $request->getContent();

        $serializerInterface->deserialize($jsonContent,Author::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $author]);

        $author->setUpdatedAt(new DateTimeImmutable());
        $doctrine->getManager()->flush();
        
        return $this->json(
            $author,
            Response::HTTP_PARTIAL_CONTENT,
            [
                'Location' => $this->generateUrl('api_authors_read', ['id' => $author->getId()])
            ],
            [
                "groups" => "app_api_author"
            ]
        );
    }

    /**
     * @Route("/{id}",name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @param AuthorRepository $authorRepository
     * @param Author $author
     */
    public function delete(Author $author = null, AuthorRepository $authorRepository)
    {
        if ($author === null){ return $this->json404(); }
        
        $authorRepository->remove($author, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                'Location' => $this->generateUrl('api_authors_browse')
            ]
        );
    }
}
