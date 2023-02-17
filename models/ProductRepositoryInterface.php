<?php

namespace models;

interface productRepositoryInterface
{
    public function getAllProducts(): array;

    public function findProductById(int $id): array;

    public function insertProduct(array $records):void;

    public function deleteProduct(string $column, array $productIds): void;
}