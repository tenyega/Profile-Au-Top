<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
