<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InscriptionRepository; // Ajoutez cette ligne

#[Route('/inscription')]
final class InscriptionController extends AbstractController
{
    #[Route(name: 'app_inscription_index', methods: ['GET'])]
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('inscription/index.html.twig', [
            'inscriptions' => $inscriptionRepository->findAll(),
        ]);
    }

    // Exemple dans votre méthode new ou edit dans InscriptionController

#[Route('/mesInscriptions', name: 'app_inscription_my')]
public function myInscription(InscriptionRepository $inscriptionRepository): Response
{

//Récupérer l'utilisateur actuellement connecté
$user=$this->getUser();
//Récupérer les inscriptions de l'utilisateur
$userInscriptions=$inscriptionRepository->findBy(['user'=>$user]);
//Appeler la vue

return $this->render('inscription/my_inscriptions.html.twig', [
    'inscriptions' => $userInscriptions,
]);

}




// Dans InscriptionController.php
#[Route('/inscription/new', name: 'app_inscription_new')]
public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, EvenementRepository $evenementRepository): Response
{
    $inscription = new Inscription();

    // Créez le formulaire en utilisant le type de formulaire correspondant
    $form = $this->createForm(InscriptionType::class, $inscription);
    $form->handleRequest($request);

    // Vérifiez si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist et flush l'inscription
        $entityManager->persist($inscription);
        $entityManager->flush();

        // Ajoutez un message de succès et redirigez l'utilisateur
        $this->addFlash('success', 'Inscription ajoutée avec succès !');
        return $this->redirectToRoute('app_inscription_index');
    }

    // Si le formulaire n'est pas soumis ou pas valide, affichez le formulaire
    return $this->render('inscription/new.html.twig', [
        'form' => $form->createView(),
    ]);
}




    #[Route('/{id}', name: 'app_inscription_show', methods: ['GET'])]
    public function show(Inscription $inscription): Response
    {
        return $this->render('inscription/show.html.twig', [
            'inscription' => $inscription,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inscription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inscription $inscription, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscription_delete', methods: ['POST'])]
    public function delete(Request $request, Inscription $inscription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscription->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($inscription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
    }
}
