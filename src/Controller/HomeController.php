<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(JobOfferRepository $jr): Response
    {
        $jobOffers = $jr->findAll();
        return $this->render('home/index.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }
}
