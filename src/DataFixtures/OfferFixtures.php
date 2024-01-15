<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $offer = new Offer();

        $offer->setTitle("Frontend developer - Vue");
        $offer->setDescription("We are looking for fratas");
        $offer->setLocation("Krakow - Poland");
        $offer->setTechnologies(["Vue", "Typescript"]);
        $offer->setSalary(3000);

        $manager->persist($offer);

        $manager->flush();
    }
}
