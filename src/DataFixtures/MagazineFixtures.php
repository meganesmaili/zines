<?php

namespace App\DataFixtures;

use App\Entity\Magazine;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class MagazineFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            CategoriesFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void

    {
        //Initialisation de Faker

        $faker = Faker\Factory::create();  

        for ($i=0; $i<10; $i++){

            //Date aléatoire

            $date = new DateTimeImmutable();
            $randDate = $date->modify('-'. rand(1, 600) .' days');

            //Instancie l'entité avec laquelle travailler
            $magazine = new Magazine();
            $magazine->setName($faker->name);
            $magazine->setPrice($faker->numberBetween(6,30));
            $magazine->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 years')));
            $magazine->setCategorie($this->getReference("categorie_".  rand(0, 14)));
            $magazine->setDescription($faker->realText(100, 2));

            $this->addReference("magazine_$i", $magazine);
            // Met de côté les données en attente d'insertion
            $manager->persist($magazine);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
