<?php

namespace Controllers;

use http\JsonResponse;
use models\{ProductRepository};
use validation\BookValidation;
use validation\DVDValidation;
use validation\FurnitureValidation;


class ProductsController extends ProductRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function template()
    {

        echo file_get_contents(__DIR__.'/../fronted/dist/index.html');
    }

    public function index()
    {
        $products = $this->getAllProducts();
        echo (new JsonResponse())->response('200', $products);
    }

    public function store($request)
    {
        switch ($request['type']) {
            case $this::PRODUCT_TYPES['DVD']:
                $validation = (new DVDValidation())->validate($request);
                break;
            case $this::PRODUCT_TYPES['BOOK']:
                $validation = (new BookValidation())->validate($request);
                break;
            case $this::PRODUCT_TYPES['FURNITURE']:
                $validation = (new FurnitureValidation())->validate($request);
                break;
        }

        if (!empty($validation)) {
            echo (new JsonResponse())->response('403', $validation);
            return;
        }

        $this->insertProduct($request);
    }

    public function delete($request)
    {
        $this->deleteProduct('id', array_values($request['products']));
    }
}