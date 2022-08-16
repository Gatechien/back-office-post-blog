<?php

namespace App\Controller\Api;

use App\Entity\Tag;
use App\Repository\TagRepository;
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
 * @Route("/api/tag", name="api_tags_")
 */
class TagController extends ApiController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function browse(TagRepository $tagRepository): JsonResponse
    {
        $tags = $tagRepository->findAll();

        return $this->json(
            $tags,
            Response::HTTP_OK,
            [],
            ["groups" =>[
                "app_api_tag_browse"
            ]]
        );
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Tag $tag = null): JsonResponse
    {
        if($tag === null)
        {
            return $this->json404();
        }
        return $this->json200(
            $tag,
            ["groups" => [
                "app_api_tag"
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
            $newTag = $serializerInterface->deserialize($jsonContent, Tag::class, 'json');
        }
        catch(Exception $e) 
        {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $errors = $validator->validate($newTag);
        
        if (count($errors)> 0)
        {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em = $manager->getManager();
        $em->persist($newTag);
        $em->flush();

        return $this->json(
            $newTag,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_tags_read', ['id' => $newTag->getId()])
            ],
            [
                "groups" => "app_api_tag"
            ]
        );
    }

    /**
     * @Route("/{id}",name="edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(Tag $tag = null, Request $request, ManagerRegistry $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        if ($tag === null){ return $this->json404(); }

        $jsonContent = $request->getContent();

        $serializerInterface->deserialize($jsonContent,Tag::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $tag]);

        $doctrine->getManager()->flush();
        
        return $this->json(
            $tag,
            Response::HTTP_PARTIAL_CONTENT,
            [
                'Location' => $this->generateUrl('api_tags_read', ['id' => $tag->getId()])
            ],
            [
                "groups" => "app_api_tag"
            ]
        );
    }

    /**
     * @Route("/{id}",name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @param TagRepository $tagRepository
     * @param Tag $tag
     */
    public function delete(Tag $tag = null, TagRepository $tagRepository)
    {
        if ($tag === null){ return $this->json404(); }
        
        $tagRepository->remove($tag, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                'Location' => $this->generateUrl('api_tags_browse')
            ]
        );
    }
}
