<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

// use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
	
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class ApiPostController extends AbstractController
{
    /**
     * @Route("/api/post", name="api_post", methods={"GET"})
     */
    public function index(PostRepository $postRepository, SerializerInterface $serializer): Response
    {
        $post = $postRepository->findAll();

        $json = $serializer->serialize($post, 'json', ["groups" => "GetPost"]);

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/post", name="api_post_create", methods={"POST"})
     */
    public function post(Request $request, EntityManagerInterface $em):response 

    {
        $data = json_decode($request->getContent(), true);

        // $category = $this->getCategory();

        $post = new Post();
        $post->setTitle($data["title"]);
        $post->setContent($data["content"]);
        $post->setAuthor($data["author"]);
        $post->setCategory($post->getCategory()->getId(2));

        $em->persist($post);
        $em->flush();

        return $response = new response('Hello ', response::HTTP_OK);
    }

     /**
     * @Route("/api/post/{id}", name="api_sujet_read_single", methods={"GET"})
     */
    public function read_single(int $id, SerializerInterface $serializer, PostRepository $postRepository ): Response
    {
        $post = $postRepository->find($id);

        $notFound = new JsonResponse(array('message' => 'Not Found',), 404 ,[], false);
        $succes = new JsonResponse($serializer->serialize($post, 'json', ["groups" => "GetPost"]), 200, [], true);

        !$post ? $response = $notFound : $response = $succes;

        return $response;
    }

    /**
     * @Route("/api/post/edit/{id}", name="post_edit", methods={"PUT"})
     */
    public function editPost(int $id, ManagerRegistry $doctrine, Request $request):Response
    {
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);

        if (!$post) {
            return $response = new JsonResponse(array('message' => 'Not found',), 404 ,[], false);
        }

        $response = new JsonResponse(array('message' => 'Data Update',), 200 ,[], false);

        $data = json_decode($request->getContent(), true);

        $post->setTitle($data["title"]);
        $post->setContent($data["content"]);

        $entityManager->flush();

        return $response;
    }
}
