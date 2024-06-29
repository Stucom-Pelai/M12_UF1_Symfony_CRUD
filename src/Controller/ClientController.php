<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Entity\Shop;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): JsonResponse
    {
        return $this->json($clientRepository->findAll(), Response::HTTP_OK);
    }

    #[Route('/new', name: 'app_client_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $client = new Client();
        $client->setName($data['name']);

        $entityManager->persist($client);
        $entityManager->flush();

        return $this->json(['message' => 'Client added successfully.'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(int $id, ClientRepository $repository): JsonResponse
    {
        $client = $repository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($client, Response::HTTP_OK);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id, ClientRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $client = $repository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);        
        $client->setName($data['name']);

        $entityManager->flush();

        return $this->json(['message' => 'Client updated successfully.'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['DELETE'])]
    public function delete(int $id, ClientRepository $repository , EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $repository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Client not found.'],Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['message' => 'Client deleted successfully.'],Response::HTTP_OK);
    }

    #[Route('/{id}/shop', name: 'app_client_edit_shop', methods: ['PUT'])]
    public function editShop(Request $request, int $id, ClientRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $client = $repository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);   

        // find shop by ID
        $shop = $entityManager->getRepository(Shop::class)->find($data['shop']);
        if (!$shop) {
            return $this->json(['message' => 'Shop not found.'], Response::HTTP_NOT_FOUND);
        }

        $client->addShop($shop);

        $entityManager->flush();

        return $this->json(['message' => 'Shop added to Client successfully.'], Response::HTTP_OK);
    }
}
