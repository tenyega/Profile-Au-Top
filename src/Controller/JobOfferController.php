<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Enum\JobStatus;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class JobOfferController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/job-offers', name: 'app_job_offer', methods: ['GET'])]
    public function list(JobOfferRepository $jr): Response
    {
        $jobOffers = $jr->findAll();
        return $this->render('job_offer/list.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/job-offers/new', name: 'app_job_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer); // Chargement du formulaire
        $form = $form->handleRequest($request); // Recuperation des données de la requête POST

        // Traitement des données
        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setStatus(JobStatus::A_POSTULER);
            $em->persist($jobOffer);
            $em->flush();
            $this->addFlash('success', 'Your note has been created successfully'); // added a flash with this method but this needs to be shown to the user inside the twig file  also . 
            return $this->redirectToRoute('app_home');
        }
        return $this->render('job_offer/new.html.twig', [
            'jobOfferForm' => $form
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/job-offers/{id}', name: 'app_job_offer_show', methods: ['GET'])]
    public function show(string  $id, JobOfferRepository $jr): Response
    {
        $jobOffer = $jr->findOneBy(['id' => $id]);
        return $this->render('job_offer/show.html.twig', [
            'jobOffer' => $jobOffer,
        ]);
    }
}
