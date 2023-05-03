<?php

namespace App\Manager;

use App\Repository\ProductRepository;

class ProductManager
{

    private $productRepository;

    public function __construct(
        ProductRepository $productRepository,

    ) {
        $this->productRepository = $productRepository;
    }


    public function getCartProducts($session): array
    {
        $cart = $session->get('cart');
        $productIds = $cart ? array_keys($cart) : [];
        $products = [];
        $getProducts = $productIds ? $this->productRepository->findByIds($productIds) : [];
        foreach ($getProducts as $product) {
            $products[] = [
                'id' => $product->getId(),
                'productName' => $product->getProductName(),
                'qty' => $cart[$product->getId()] ? $cart[$product->getId()]['qty'] : 1,
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'total' => $cart[$product->getId()] ? round($cart[$product->getId()]['qty'] * $product->getPrice(), 2) : 0.00,
                'size' => $cart[$product->getId()] ? $cart[$product->getId()]['size'] : '',

            ];
        }
        return $products;
    }

    public function getTotalAmount($products, $session): float
    {
        $cart = $session->get('cart');
        $total = 0;
        foreach ($products as $product) {
            $total += $cart[$product['id']] ? $cart[$product['id']]['qty'] * $product['price'] : 0.00;
        }
        return round($total, 2);
    }
}
