<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Enum\JobStatus;
use App\Form\JobOfferType;
use App\Repository\CoverLetterRepository;
use App\Repository\JobOfferRepository;
use App\Repository\LinkedInMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

#[IsGranted('ROLE_USER')]
class JobOfferController extends AbstractController
{
    #[Route('/job-offers', name: 'app_job_offer', methods: ['GET'])]
    public function list(JobOfferRepository $jr): Response
    {
        $user = $this->getUser();
        $jobOffers = $jr->findBy(['app_user' => $user]);
        return $this->render('job_offer/list.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }


    #[Route('/job-offers/new', name: 'app_job_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer); // Chargement du formulaire
        $form = $form->handleRequest($request); // Recuperation des données de la requête POST

        // Traitement des données
        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setAppUser($this->getUser());
            $em->persist($jobOffer);
            $em->flush();
            $this->addFlash('success', 'Your note has been created successfully'); // added a flash with this method but this needs to be shown to the user inside the twig file  also . 
            return $this->redirectToRoute('app_home');
        }
        return $this->render('job_offer/new.html.twig', [
            'form' => $form
        ]);
    }



    #[Route('/job-offers/{id}', name: 'app_job_offer_show', methods: ['GET'])]
    public function show(string  $id, JobOfferRepository $jr, CoverLetterRepository $clr, LinkedInMessageRepository $lmr): Response
    {
        $jobOffer = $jr->findOneBy(['id' => $id]);

        $coverLetters = $clr->findBy([
            'jobOffer' => $jobOffer,
            'app_user' => $this->getUser()
        ]);

        $linkedInMessages = $lmr->findBy([
            'jobOffer' => $jobOffer,
            'app_user' => $this->getUser()
        ]);
        return $this->render('job_offer/show.html.twig', [
            'jobOffer' => $jobOffer,
            'coverLetters' => $coverLetters,
            'linkedInMessages' => $linkedInMessages
        ]);
    }

    #[Route('/job-offers/{id}/edit', name: 'app_job_offer_edit', methods: ['GET', 'POST'])]
    public function edit(string  $id, Request $request, EntityManagerInterface $em, JobOfferRepository $jr): Response
    {
        $jobOffer = $jr->findOneBy(['id' => $id]);
        $form = $this->createForm(JobOfferType::class, $jobOffer); // Chargement du formulaire
        $form = $form->handleRequest($request); // Recuperation des données de la requête POST

        // Traitement des données
        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setAppUser($this->getUser());
            $em->persist($jobOffer);
            $em->flush();
            $this->addFlash('success', 'Your note has been created successfully'); // added a flash with this method but this needs to be shown to the user inside the twig file  also . 
            return $this->redirectToRoute('app_home');
        }

        return $this->render('job_offer/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/job-offers/{id}/delete', name: 'app_job_offer_delete', methods: ['GET', 'POST'])]
    public function delete(string  $id, JobOfferRepository $jr): Response
    {
        $jobToDelete = $jr->findOneBy(['id' => $id]);
        return $this->render('job_offer/delete.html.twig', [
            'jobOffer' => $jobToDelete,
        ]);
    }

    #[Route('/updateJobStatus', name: 'app_update_job_status', methods: ['GET', 'POST'])]
    public function update(string  $id, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $jobId = $data['id'];
        $newStatus = $data['status'];

        // Find the job entity by its ID
        $job = $em->getRepository(JobOffer::class)->find($jobId);

        if (!$job) {
            return new JsonResponse(['error' => 'Job not found'], 404);
        }

        // Update the job status
        $job->setStatus($newStatus); // Adjust according to your entity method

        // Persist changes to the database
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Job status updated successfully']);
    }
}
