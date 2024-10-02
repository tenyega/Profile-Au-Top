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
    #[Route('/linkedin-message/g/generate', name: 'linked_in_message_generate', methods: ['POST'])]
    public function generate(): Response
    {
        return $this->render('linked_in_message/generate.html.twig');
    }

    #[Route('/linkedin-message/{id}', name: 'linked_in_message_show', methods: ['GET'])]
    public function show(LinkedInMessageRepository $lmr, string $id): Response
    {
        $linkedInMessage = $lmr->findOneBy(['id' => $id]);
        return $this->render('linked_in_message/show.html.twig', [
            'linkedInMessage' => $linkedInMessage,
        ]);
    }
}
