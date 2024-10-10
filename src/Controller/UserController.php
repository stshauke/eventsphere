<?php
// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/liste', name: 'app_users_list')]
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        // Récupérer tous les utilisateurs
        $allUsers = $this->entityManager->getRepository(User::class)->findAll();

        // Filtrer les utilisateurs sans le rôle "ROLE_ADMIN"
        $filteredUsers = array_filter($allUsers, function ($user) {
            return !in_array('ROLE_ADMIN', $user->getRoles());
        });

        // Convertir en tableau pour la pagination
        $filteredUsers = array_values($filteredUsers);

        // Paginer les utilisateurs filtrés
        $pagination = $paginator->paginate(
            $filteredUsers, 
            $request->query->getInt('page', 1),
            3 //Nombre des lignes a afficher dans chaque formulaire
        );

        return $this->render('user/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
