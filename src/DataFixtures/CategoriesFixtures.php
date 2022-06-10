<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i=0; $i <15 ; $i++) { 
            
            $categorie = new Categorie();
            $categorie->setName($faker->company);
            $categorie->setColor($faker->hexcolor);

            $this->addReference("categorie_$i", $categorie);

            $manager->persist($categorie);
        }

        $manager->flush();
    }
}
