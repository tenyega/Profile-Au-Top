<?php

namespace App\Controller;

use App\Repository\CoverLetterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CoverLetterController extends AbstractController
{
    #[Route('/cover-letter/g/generate', name: 'app_cover_letter', methods: ['POST'])]
    public function generate(): Response
    {
        return $this->render('cover_letter/generate.html.twig');
    }

    #[Route('/cover-letter/{id}', name: 'app_cover_letter_show', methods: ['GET'])]
    public function show(CoverLetterRepository $clr, string $id): Response
    {
        $coverLetter = $clr->findOneBy(['id' => $id]);
        return $this->render('cover_letter/show.html.twig', [
            'coverLetter' => $coverLetter
        ]);
    }

    #[Route('/cover-letter/d/{id}', name: 'app_cover_letter_delete', methods: ['GET'])]
    public function delete(CoverLetterRepository $clr, string $id): Response
    {
        $coverLetter = $clr->deleteCoverLetter(['id' => $id]);

        $this->addFlash('success', 'The selected cover letter has been deleted');
        return $this->redirectToRoute('app_home');
    }
}
