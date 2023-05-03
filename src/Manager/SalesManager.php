<?php

namespace App\Manager;

use App\Entity\Sales;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;
use Doctrine\Persistence\ManagerRegistry;

class SalesManager
{

    private $doctrine;

    public function __construct(
        ManagerRegistry $doctrine,


    ) {
        $this->doctrine = $doctrine;
    }

    public function createSale($user,$totalAmount): Sales
    {
        $sales = new Sales();
        $sales->setUser($user);
        $sales->setTotalAmount($totalAmount);
        $sales->setCreatedAt(new \DateTime());
        $entityManager =  $this->doctrine->getManager();
        $entityManager->persist($sales);
        $entityManager->flush();
        return $sales;
    }
}
