<?php

namespace App\Controller;

use App\Repository\LinkedInMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class LinkedInMessageController extends AbstractController
{
    #[Route('/linkedin-message/g/generate', name: 'linked_in_message_generate', methods: ['POST', 'GET'])]
    public function generate(): Response
    {
        return $this->render('linked_in_message/generate.html.twig');
    }

    #[Route('/linkedin-message/{id}', name: 'linked_in_message_show', methods: ['GET'])]
    public function show(LinkedInMessageRepository $lmr, string $id): Response
    {
        $linkedInMessages = $lmr->findBy(['app_user' => $id]);
        return $this->render('linked_in_message/show.html.twig', [
            'linkedInMessages' => $linkedInMessages,
        ]);
    }


    #[Route('/linkedin-message/d/{id}', name: 'linked_in_message_delete', methods: ['GET'])]
    public function delete(LinkedInMessageRepository $lmr, string $id): Response
    {
        $linkedInMessage = $lmr->deleteLinkedInMessage(['id' => $id]);
        dd($linkedInMessage);
        $this->addFlash('success', 'The selected cover letter has been deleted');
        return $this->redirectToRoute('app_home');
    }
}
