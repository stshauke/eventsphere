<?php

namespace App\Controller;

use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

#[Route('/evenement')]
final class EvenementController extends AbstractController
{
    #[Route(name: 'app_evenement_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search');
        $currentPage = $request->query->getInt('page', 1);
        $itemsPerPage = 4;

        // Créer une requête Doctrine pour récupérer les événements avec leurs inscriptions
        $queryBuilder = $entityManager->getRepository(Evenement::class)
            ->createQueryBuilder('e')
            ->leftJoin('e.inscriptions', 'i')
            ->addSelect('i') // Inclure les inscriptions dans la requête
            ->orderBy('e.id', 'ASC');

        if ($searchTerm) {
            $queryBuilder
                ->andWhere('e.nomEvenement LIKE :searchTerm OR e.descriptionEvenement LIKE :searchTerm OR e.lieuEvenement LIKE :searchTerm OR e.dateEvenement LIKE :searchTerm OR e.nbMaxParticipants LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Pagination des résultats
        $pagination = $paginator->paginate(
            $queryBuilder,
            $currentPage,
            $itemsPerPage
        );

        return $this->render('evenement/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('app_home'));
        }

        $evenement = new Evenement();

        // Créer manuellement le formulaire
        $form = $this->createFormBuilder($evenement)
            ->add('nomEvenement', TextType::class, ['label' => 'Nom de l\'événement'])
            ->add('descriptionEvenement', TextareaType::class, ['label' => 'Description'])
            ->add('dateEvenement', DateTimeType::class, ['widget' => 'single_text', 'label' => 'Date de l\'événement'])
            ->add('lieuEvenement', TextType::class, ['label' => 'Lieu de l\'événement'])
            ->add('nbMaxParticipants', IntegerType::class, ['label' => 'Nombre maximum de participants'])
            ->add('image', FileType::class, [
                'label' => 'Image de l\'événement',
                'mapped' => false,
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                // Déplace le fichier dans le répertoire configuré
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Erreur lors du téléchargement de l\'image.');
                }

                // Enregistrer le nom de l'image dans l'entité
                $evenement->setImage($newFilename);
            }

            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
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
            return new RedirectResponse($this->generateUrl('app_home'));
        }

        $form = $this->createFormBuilder($evenement)
            ->add('nomEvenement', TextType::class, ['label' => 'Nom de l\'événement'])
            ->add('descriptionEvenement', TextareaType::class, ['label' => 'Description'])
            ->add('dateEvenement', DateTimeType::class, ['widget' => 'single_text', 'label' => 'Date de l\'événement'])
            ->add('lieuEvenement', TextType::class, ['label' => 'Lieu de l\'événement'])
            ->add('nbMaxParticipants', IntegerType::class, ['label' => 'Nombre maximum de participants'])
            ->add('image', FileType::class, [
                'label' => 'Image de l\'événement',
                'mapped' => false,
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                // Déplace le fichier dans le répertoire configuré
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Erreur lors du téléchargement de l\'image.');
                }

                // Enregistrer le nom de l'image dans l'entité
                $evenement->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('app_home'));
        }

        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
