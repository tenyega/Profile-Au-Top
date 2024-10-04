<?php

namespace App\Controller;

use App\Enum\JobStatus;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class KanbanController extends AbstractController
{
    #[Route('/kanban', name: 'app_kanban', methods: ['GET'])]
    public function index(JobOfferRepository $jor): Response
    {
        $user = $this->getUser();
        $jobOfferEnAttentes = $jor->findBy((['app_user' => $user, 'status' => 'En Attente']));
        $jobOfferAPostulers = $jor->findBy((['app_user' => $user, 'status' => 'A Postuler']));
        $jobOfferEntretiens = $jor->findBy((['app_user' => $user, 'status' => 'Entretien']));
        $jobOfferAcceptes = $jor->findBy((['app_user' => $user, 'status' => 'Accepté']));
        $jobOfferRefuses = $jor->findBy((['app_user' => $user, 'status' => 'Refusé']));

        return $this->render('kanban/index.html.twig', [
            'jobOfferEnAttentes' => $jobOfferEnAttentes,
            'jobOfferAPostulers' => $jobOfferAPostulers,
            'jobOfferEntretiens' => $jobOfferEntretiens,
            'jobOfferAcceptes' => $jobOfferAcceptes,
            'jobOfferRefuses' => $jobOfferRefuses

        ]);
    }
    #[Route('/update-status', name: 'app_kanban_updateStatus', methods: ['POST'])]
    public function updateStatus(Request $request, EntityManagerInterface $em, JobOfferRepository $jor)
    {

        $data = json_decode($request->getContent(), true);

        // Validate data
        if (!isset($data['id']) || !isset($data['status'])) {
            return new JsonResponse(['error' => 'Invalid input'], 400);
        }

        try {
            // Assume you have a Job entity
            $job = $jor->find($data['id']);
            if (!$job) {
                return new JsonResponse(['error' => 'Job not found'], 404);
            }

            // Update the job status
            switch ($data['status']) {
                case 'A postuler':
                    $status = JobStatus::A_POSTULER;
                    break;
                case 'En attente':
                    $status = JobStatus::EN_ATTENTE;
                    break;
                case 'Entretien':
                    $status = JobStatus::ENTRETIEN;
                    break;
                case 'Refusé':
                    $status = JobStatus::REFUSE;
                    break;
                case 'Accepté':
                    $status = JobStatus::ACCEPTE;
                    break;
                default:

                    break;
            }
            $job->setStatus($status);
            $em->persist($job);
            $em->flush();

            return new JsonResponse(['status' => 'success']); // Return a success response
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500); // Log the exception message
        }
    }
}
