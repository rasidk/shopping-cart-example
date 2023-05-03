<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager): void
    {

        //Create Admin
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setFirstName('Admin');
        $user->setRoles(["ROLE_ADMIN"]);
        $newEncodedPassword = $this->passwordEncoder->encodePassword($user,  '123456');
        $user->setPassword($newEncodedPassword);
        $manager->persist($user);
        $manager->flush();

         //Create User
         $user = new User();
         $user->setEmail('user@example.com');
         $user->setFirstName('User');
         $user->setRoles([]);
         $user->setCredits(1000);
         $newEncodedPassword = $this->passwordEncoder->encodePassword($user,  '123456');
         $user->setPassword($newEncodedPassword);
         $manager->persist($user);
         $manager->flush();


        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setProductName('Product ' . $i);
            $product->setPrice(mt_rand(10, 100));
            $product->setSizes(['S','M','L']);
            $product->setDescription('Test Product');
            $product->setCreatedAt(new \DateTime());
            $manager->persist($product);
        }

        $manager->flush();
    }
}
