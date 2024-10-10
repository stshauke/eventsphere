<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/evenement')]
final class EvenementController extends AbstractController
{

    #[Route(name: 'app_evenement_index', methods: ['GET'])]
    public function index(Request $request,EvenementRepository $evenementRepository, PaginatorInterface $paginator): Response
    {
       
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search');
        // Récupérez la page actuelle à partir de la requête
        $currentPage = $request->query->getInt('page', 1);

        // Nombre d'éléments par page
        $itemsPerPage = 4;
    // Créez une requête Doctrine pour récupérer les annonces (alias: an)
    // Utilisez le repository pour récupérer les modules
    $queryBuilder = $evenementRepository->createQueryBuilder('e')
        ->orderBy('e.id', 'ASC');
// Ajoutez une condition de recherche si un terme de recherche est spécifié
if ($searchTerm) {
    $queryBuilder
        ->andWhere('e.nomEvenement LIKE :searchTerm OR e.descriptionEvenement LIKE :searchTerm OR e.lieuEvenement LIKE :searchTerm OR e.dateEvenement LIKE :searchTerm OR e.nbMaxParticipants LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $searchTerm . '%');
}
     // Paginez les résultats
     $pagination = $paginator->paginate(
        $queryBuilder,
        $currentPage,
        $itemsPerPage
    );
    // Transférez la pagination à votre vue Twig
        
        
        
        
        return $this->render('evenement/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN')) {
            $accessDeniedRoute = $this->generateUrl('app_home');
            return new RedirectResponse($accessDeniedRoute);
        }


        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            $accessDeniedRoute = $this->generateUrl('app_home');
            return new RedirectResponse($accessDeniedRoute);
        }
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

         // Debugging
    // dump($evenement); // Vérifiez que l'entité contient les données correctes
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN')) {
            $accessDeniedRoute = $this->generateUrl('app_home');
            return new RedirectResponse($accessDeniedRoute);
        }
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
