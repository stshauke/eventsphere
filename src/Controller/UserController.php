<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $entityManager;
    private $userRepository;
    private $inscriptionRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, InscriptionRepository $inscriptionRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->inscriptionRepository = $inscriptionRepository;
    }

    #[Route('/users', name: 'app_users_list')]
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        // Requête pour récupérer tous les utilisateurs
        $queryBuilder = $this->userRepository->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');

        // Filtrer les utilisateurs par recherche si nécessaire
        $searchTerm = $request->query->get('search');
        if ($searchTerm) {
            $queryBuilder->where('u.email LIKE :searchTerm OR u.nom LIKE :searchTerm')
                         ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10 // Nombre de résultats par page
        );

        // Ajouter le nombre d'inscriptions pour chaque utilisateur
        foreach ($pagination as $user) {
            $user->inscriptionsCount = $this->inscriptionRepository->count(['user' => $user]);
        }

        return $this->render('user/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
