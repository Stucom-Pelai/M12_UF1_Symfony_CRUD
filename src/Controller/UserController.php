<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * Get all users
     */
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(), Response::HTTP_OK);
    }

    /**
     * Create user
     */
    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setName($data['name']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'User added successfully.'], Response::HTTP_CREATED);
    }

    /**
     * Get user by id
     */
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, UserRepository $repository): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($user, Response::HTTP_OK);
    }

    /**
     * Edit user by id
     */
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id, UserRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $user->setName($data['name']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $entityManager->flush();

        return $this->json(['message' => 'User updated successfully.'], Response::HTTP_OK);
    }

    /**
     * Delete user by id
     */
    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(int $id, UserRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'User deleted successfully.'], Response::HTTP_OK);
    }

    /**
     * Get user by name
     */
    #[Route('/name/{name}', name: 'app_user_show_name', methods: ['GET'])]
    public function showByName(string $name, UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findBy(array('name'=> $name)), Response::HTTP_OK);
    }

    /**
     * Get all users by password order by id desc
     */
    #[Route('/password/{pw}/desc', name: 'app_user_show_password_desc', methods: ['GET'])]
    public function indexDesc(string $pw, UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findBy(array('password' => $pw),array('id' => 'DESC')), Response::HTTP_OK);
    }

     /**
     * Get user by email
     */
    #[Route('/email/{email}', name: 'app_user_show_email', methods: ['GET'])]
    public function showByEmail(string $email, UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findByEmail($email), Response::HTTP_OK);
    }

     /**
     * Get users by email domain
     */
    #[Route('/domain/{email}', name: 'app_user_index_domain', methods: ['GET'])]
    public function indexByEmail(string $email, UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findByEmailDomain($email), Response::HTTP_OK);
    }
}
