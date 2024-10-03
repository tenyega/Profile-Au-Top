<?php

namespace App\Controller;

use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ApiJobOfferController extends AbstractController
{
    #[Route('/api/job-offers/update-status', name: 'app_api_job_offer', methods: ['POST'])]
    public function updateStatus(): Response
    {
        return $this->render('api_job_offer/updateStatus.html.twig', [
            'API' => 'ApiJobOfferController',
        ]);
    }

    #[Route('/ask', name: 'app_api_job', methods: ['GET'])]
    public function ask(): Response
    {
        return $this->render('ask.html.twig');
    }

    #[Route('/generate-cover-letter', name: 'generate_cover_letter', methods: ['POST'])]
    public function generateCoverLetter(Request $request, OpenAIService $openAIService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $companyName = $data['companyName'] ?? 'the company';

        $prompt = "Generate a complete and professional cover letter for a job application to {$companyName}. The cover letter should:
        1. Be around 250-300 words long
        2. Have a proper structure with introduction, body, and conclusion
        3. Be engaging and highlight general qualifications without mentioning specific skills or experiences
        4. Use a professional tone
        5. Not include placeholder text like [Your Name] or [Your Address]
        6. Be ready to use with minimal editing

        Begin the letter with 'Dear Hiring Manager,' and end with 'Sincerely,'";

        try {
            $coverLetter = $openAIService->generateText($prompt, [
                'model' => 'gpt-3.5-turbo-instruct',  // or 'gpt-4' if available
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);
            dd($coverLetter);
            return new JsonResponse(['coverLetter' => $coverLetter]);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
