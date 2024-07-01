<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Shipment;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        return $this->json($productRepository->findAll(), Response::HTTP_OK);
    }

    #[Route('/new', name: 'app_product_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
       
        // find category by ID
        $category = $entityManager->getRepository(Category::class)->find($data['category']);
        if (!$category) {
            return $this->json(['message' => 'Category not found.'], Response::HTTP_NOT_FOUND);
        }
        
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        // relates this product to the category
        $product->setCategory($category);

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json(['message' => 'Product added successfully.'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(int $id, ProductRepository $repository): JsonResponse
    {
        $product = $repository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($product, Response::HTTP_OK);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id, ProductRepository $repository,  EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $repository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // find category by ID
        $category = $entityManager->getRepository(Category::class)->find($data['category']);
        if (!$category) {
            return $this->json(['message' => 'Category not found.'], Response::HTTP_NOT_FOUND);
        }        
        
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        // relates this product to the category
        $product->setCategory($category);
        $entityManager->flush();

        return $this->json(['message' => 'Product updated successfully.'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['DELETE'])]
    public function delete(int $id, ProductRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $repository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found.'],Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['message' => 'Product deleted successfully.'],Response::HTTP_OK);
    }

    #[Route('/{id}/ship', name: 'app_product_ship', methods: ['PUT'])]
    public function ship(int $id, ProductRepository $repository,  EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $repository->find($id);

        if (!$product) {
            return $this->json(['message' => 'Product not found.'], Response::HTTP_NOT_FOUND);
        }
        
        $shipment = new Shipment();
        $shipment->setDate(new \DateTime());
        $product->setShipment($shipment);

        $entityManager->flush();

        return $this->json(['message' => 'Product shipped successfully.'], Response::HTTP_OK);
    }
}
