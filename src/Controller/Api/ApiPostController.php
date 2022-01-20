<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiPostController extends AbstractController
{
    /**
     * @Route("/api/post", name="api_post", methods={"GET"})
     */
    public function index(PostRepository $postRepository, SerializerInterface $serializer): Response

    {
        $categoryAll = $postRepository->findAll();

        $json = $serializer->serialize($categoryAll, 'json', ["groups" => "GetPost"]);

        $response = new JsonResponse($json, 200, [], true);

        return $response;
    }

    /**
     * @Route("/api/post", name="api_post_create", methods={"POST"})
     */
    public function post(Request $request, EntityManagerInterface $em):response 

    {
        $parameters = json_decode($request->getContent(), true);

        $post = new Post();
        $post->setTitle($parameters["title"])
        ->setContent($parameters["content"])
        ->setAuthor($parameters["author"]);
        $em->persist($post);
        $em->flush();

        return $response = new response('Hello ', response::HTTP_OK);
    }

     /**
     * @Route("/api/post/{id}", name="api_sujet_read_single", methods={"GET"})
     */
    public function read_single(Request $request, $id, SerializerInterface $serializer, PostRepository $postRepository ): Response
    {

        $post = $postRepository->find($request->get("id"));

        if(isset($post)) {

            $json = $serializer->serialize($post, 'json', ["groups" => "GetPost"]);
            
            $response = new JsonResponse($json, 200, [], true);

            return $response;
        }else {
            $response = new JsonResponse(array('message' => 'Not Found',), 404 ,[], false);

            return $response;
        }
    }
}
