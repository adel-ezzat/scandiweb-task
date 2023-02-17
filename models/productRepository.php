<?php

namespace models;

use database\Database;

/**
 * product repository
 */
class productRepository implements productRepositoryInterface
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database('products');
    }

    public const PRODUCT_TYPES = [
        'DVD'       => '0',
        'BOOK'      => '1',
        'FURNITURE' => '2'
    ];

    /**
     * get all products records
     * @return array
     */
    public function getAllProducts(): array
    {
        return $this->database
        ->select(['*'])
        ->get();
    }

    /**
     * get product by id
     * @param int $id
     * @return array
     */
    public function findProductById(int $id): array
    {
        return $this->database
            ->select(['*'])
            ->where([
                ['id', '=', $id]
            ])->get();
    }

    /**
     * insert new product
     * @param array $records
     * @return void
     */
    public function insertProduct(array $records): void
    {
        $this->database
            ->insert($records)
            ->execute();
    }

    /**
     * delete product
     * @param string $column
     * @param array $productIds
     * @return void
     */
    public function deleteProduct(string $column, array $productIds): void
    {
        $this->database
            ->delete()
            ->whereIn($column, $productIds)
            ->execute();
    }
}