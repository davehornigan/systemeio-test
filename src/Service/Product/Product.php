<?php

namespace App\Service\Product;

use App\Service\Product\Exception\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Exception;

class Product implements ProductInterface
{
    private ObjectRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $entityManager->getRepository(\App\Entity\Product::class);
    }

    public function getProduct(string $productId): ValueObject\Product
    {
        try {
            $product = $this->repository->find($productId);
        } catch (Exception $e) {
            throw new NotFoundException('Product not found.', previous: $e);
        }
        if ($product !== null) {
            return new ValueObject\Product(
                $product->getId(),
                $product->getName(),
                $product->getPrice(),
            );
        }

        throw new NotFoundException('Product not found.');
    }
}
