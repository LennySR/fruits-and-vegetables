<?php

namespace App\Controller;

use App\Service\StorageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiController extends AbstractController
{
    private $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }



    /**
     * @Route("/", name="api_process_file", methods={"GET"})
     */
    public function processFile(): JsonResponse
    {
        $filePath = __DIR__ . "../../../request.json";
        if (!file_exists($filePath)) {
            echo "File not found: $filePath";
            exit;
        }

        // Read the content of the actual request.json file
        $jsonContent = file_get_contents($filePath);
        $this->storageService->processRequest($jsonContent);

        // Get the stored collections
        $fruits = $this->storageService->getFruitsCollection()->list();
        $vegetables = $this->storageService->getVegetablesCollection()->list();

        $response = [
            'fruits' => $fruits,
            'vegetables' => $vegetables,
        ];

        return new JsonResponse($response);
    }
}