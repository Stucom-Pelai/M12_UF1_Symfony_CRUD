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
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(),Response::HTTP_OK);
    }

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

        return $this->json(['message' => 'User added successfully.'],Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, UserRepository $repository): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found.'],Response::HTTP_NOT_FOUND);
        }
        return $this->json($user,Response::HTTP_OK);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id, UserRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found.'],Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $user->setName($data['name']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $entityManager->flush();

        return $this->json(['message' => 'User updated successfully.'],Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(int $id, UserRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found.'],Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'User deleted successfully.'],Response::HTTP_OK);
    }
}
