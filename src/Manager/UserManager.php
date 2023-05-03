<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserManager
{

    private $doctrine;

    public function __construct(
        ManagerRegistry $doctrine,


    ) {
        $this->doctrine = $doctrine;
    }

    public function updateCredit(User $user, float $totalAmount)
    {
        $user->setCredits($totalAmount);
        $entityManager =  $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
