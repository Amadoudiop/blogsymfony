<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use \datetime;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName("nom de la catégorie");
        $category->setColor("#0000FF");
        $manager->persist($category);

// create 20 Articles
        for ($i = 0; $i < 20; $i++) {
            $product = new Article();

            if ($i < 20) {
                $product->setDateEvent( new datetime('now'));
            } else {
                $product->setDateEvent(null);
            }

            $product->setTitle("titre test de l'article" . $i);
            $product->setCatchSentence("catch sentence de l'article" . $i);
            $product->setPicture("test.jpg");
            $product->setContent("contenue test de l'article" . $i);
            $product->setCategory($category);
            if ($i < 20) {
                $product->setEnabled(0);
                $product->setEvent(1);
            } else {
                $product->setEvent(0);
                $product->setEnabled(1);
            }
            $manager->persist($product);
        }

// create 20 User
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setPromotion(1999);
            $user->setFirstName("cigarillos" . $i);
            $user->setLastName("cigar" . $i);
            $user->setEnabled(1);
            $user->setUsername("cig" . $i);
            $user->setEmail($i . "@gmail.com");
            if ($i < 0) {
                $user->setEnabled(1);
            } else {
                $user->setEnabled(0);
            }

            $user->setPlainPassword("aaa");

            if ($i < 1){
                $user->addRole( "ROLE_ADMIN" );

            }
            $user->addRole("ROLE_USER");
            $manager->persist($user);
        }

        $manager->flush();
    }
}