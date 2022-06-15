<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
         // $this->getUser() permet de récupérer les données d'un user connecté
         $roles = $this->getUser()->getRoles();
        

         // Vérifie si le rôle est soit ROLE_ADMIN soit ROLE_MODERATOR
         if (in_array('ROLE_ADMIN', $roles) || in_array('ROLE_MODERATOR', $roles)) {
             $selectAllUsers = $userRepository->findAll();
         }
         else {
             $selectAllUsers = $userRepository->findBy(['owner' => $this->getUser()]);
         }

        $owner=$paginatorInterface->paginate(
            $selectAllUsers,
            $request->query->getInt('page', 1),
            $request->query->getInt('numbers', 6)
        );
        return $this->render('admin/index.html.twig', [
            'users' => $owner,
        ]);
    }

    #[Route('/admin/user/{id}/roles/{role}', name: 'app_admin_role', methods: ['POST'])]
    public function roles(User $user, string $role, UserRepository $userRepository, EmailService $emailService): JsonResponse
    {

        $user->setRoles([$role]);
        $userRepository->add($user, true);

        $emailService->sendEmail(
            $user->getEmail(),
            'Changement de rôle',
            [
                'template' => 'emails/change_role.html.twig',
                'context'=>[
                    'username'=> $user->getName()."  ".$user->getFirstName(),
                    'role'=> $role
                ]
            ]
        );


        return $this->json(['role' => $role]);
    }
}
