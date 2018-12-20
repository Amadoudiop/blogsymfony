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

// create 20 User
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setPromotion(1999);
            $user->setFirstName("cigarillos" . $i);
            $user->setLastName("cigar" . $i);
            $user->setUsername("cig" . $i);
            $user->setEmail($i . "@gmail.com");
            if ($i < 3) {
                $user->setEnabled(1);
                $user->setValidation(1);
            } else {
                $user->setEnabled(1);
                $user->setValidation(0);
            }
            $user->setPlainPassword("aaa");

            if ($i < 3) {
                $user->addRole("ROLE_ADMIN");

            }
            $user->addRole("ROLE_USER");
            $manager->persist($user);
            $users[] = $user;
        }


        // create 20 Articles
        for ($i = 0; $i < 20; $i++) {
            $article = new Article();

            if ($i < 20) {
                $article->setDateEvent(new datetime('now'));
            } else {
                $article->setDateEvent(null);
            }

            $article->setTitle("titre test de l'article" . $i);
            $article->setCatchSentence("catch sentence de l'article" . $i);
            $article->setPicture("test.jpg");
            $article->setContent("contenue test de l'article" . $i);
            $article->setCategory($category);
            $article->setUser($users[array_rand($users)]);
            if ($i < 20) {
                $article->setEnabled(0);
                $article->setEvent(1);
            } else {
                $article->setEvent(0);
                $article->setEnabled(1);
            }
            $manager->persist($article);
        }

        $manager->flush();
    }
}