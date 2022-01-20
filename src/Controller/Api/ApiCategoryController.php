<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiCategoryController extends AbstractController
{
    /**
     * @Route("/api/category", name="api", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, SerializerInterface $serializer): Response
    {
        /**
         * Require all category forum
         */
        $categoryAll = $categoryRepository->findAll();

        $json = $serializer->serialize($categoryAll, 'json', ["groups" => "GetCategory"]);

        $response = new JsonResponse($json, 200, [], true);

        return $response;

    }
}
