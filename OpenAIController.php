<?php

namespace App\Controller;

use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OpenAIController extends AbstractController
{
    private $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }


    #[Route("/answer", name: "app_answer", methods: ['POST'])]
    public function answer(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $prompt = $data['prompt'] ?? '';

        // Call OpenAI service to get a response
        $response = $this->openAIService->askOpenAI($prompt);
        dd($this->json(['response' => $response]));
        return new JsonResponse([
            'response' => $response
        ]);
    }


    #[Route("/ask", name: "openai_show_form", methods: ["GET", 'POST'])]

    public function showForm(): Response
    {
        return $this->render('ask.html.twig');
    }
}
