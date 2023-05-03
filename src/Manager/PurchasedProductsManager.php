<?php

namespace App\Manager;

use App\Entity\Product;
use App\Entity\PurchasedProducts;
use App\Entity\Sales;
use Doctrine\Persistence\ManagerRegistry;

class PurchasedProductsManager
{

    private $productRepository;

    private $salesRepository;

    private $doctrine;


    public function __construct(
        ManagerRegistry $doctrine,


    ) {
        $this->doctrine = $doctrine;
    }


    public function savePurchasedProduct(Sales $sales, Product $product, array $cart): void
    {
        $purhcasedProduct = new PurchasedProducts();
        $purhcasedProduct->setProduct($product);
        $purhcasedProduct->setSale($sales);
        $purhcasedProduct->setProductName($product->getProductName());
        $purhcasedProduct->setQty($cart['qty']);
        $purhcasedProduct->setPrice($product->getPrice());
        $purhcasedProduct->setSize($cart['size']);
        $entityManager =  $this->doctrine->getManager();
        $entityManager->persist($purhcasedProduct);
        $entityManager->flush();
    }
}
