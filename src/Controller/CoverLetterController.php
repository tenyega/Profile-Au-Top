<?php

namespace App\Controller;

use App\Repository\CoverLetterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class CoverLetterController extends AbstractController
{



    // #[Route('/cover-letter/g/generate', name: 'app_cover_letter', methods: ['POST', 'GET'])]
    // public function generate(string $symbol): Response
    // {


    //     return $this->render('cover_letter/generate.html.twig', [
    //         'generatedCoverLetter' => ''
    //     ]);
    // }

    // Déclare des variables privées pour stocker les services nécessaires
    private ConfigGeminiIA $configGeminiIA;
    private Security $security;

    // Le constructeur de la classe, appelé lors de sa création
    public function __construct(ConfigGeminiIA $configGeminiIA, Security $security)
    {
        // Stocke les services fournis dans les variables privées
        $this->configGeminiIA = $configGeminiIA;
        $this->security = $security;
    }

    // Définit une route pour la page principale du générateur de lettre de motivation
    #[Route('/cover/letter', name: 'app_cover_letter_index')]
    public function index(): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->security->getUser();
        // Affiche la page en utilisant le template 'cover_letter/show.html.twig' et passe l'utilisateur au template
        return $this->render('cover_letter/show.html.twig', ['user' => $user]);
    }

    // Définit une route pour générer la lettre de motivation (accessible uniquement via POST)
    #[Route('/cover/letter/generate', name: 'app_cover_letter_generate', methods: ['GET'])]
    public function generate(Request $request): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->security->getUser();
        // Vérifie si l'utilisateur est connecté
        if (!$user) {
            // Si non connecté, renvoie une erreur JSON avec un code 401 (non autorisé)
            return $this->json(['error' => 'User not authenticated'], 401);
        }

        // Récupère le message supplémentaire envoyé par l'utilisateur dans la requête
        // $additionalInfo = $request->request->get('message');
        $additionalInfo = "write a cover letter for [My Company]";

        // Utilise ConfigGeminiIA pour générer la lettre de motivation
        $coverLetter = $this->configGeminiIA->generateCoverLetter($user, $additionalInfo);

        // Renvoie la lettre générée au format JSON
        $generatedCoverLetter = $this->json(['response' => $coverLetter]);
        $coverLetter = json_decode($generatedCoverLetter->getContent(), true);

        return $this->render('cover_letter/generate.html.twig', [
            'coverLetter' => $coverLetter
        ]);
    }




















    #[Route('/cover-letter/all', name: 'app_cover_letter_all', methods: ['GET'])]
    public function all(CoverLetterRepository $clr): Response
    {
        $coverLetters = $clr->findBy(['app_user' => $this->getUser()]);
        return $this->render('cover_letter/all.html.twig', [
            'coverLetters' => $coverLetters
        ]);
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
