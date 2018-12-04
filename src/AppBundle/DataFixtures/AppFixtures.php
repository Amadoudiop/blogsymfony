<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName("nom de la catégorie");
        $category->setColor("#0000FF");
        $manager->persist($category);
// create 20 Articles! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Article();
            $product->setDateEvent(null);
            $product->setTitle("titre test de l'article" . $i);
            $product->setCatchSentence("catch sentence de l'article" . $i);
            $product->setPicture("test.jpg");
            $product->setContent("contenue test de l'article" . $i);
            $product->setStatus(1);
            $product->setCategory($category);
            $product->setEvent(0);
            $manager->persist($product);
        }

        $manager->flush();
    }
}