<?php

namespace App\DataFixtures;

use App\Entity\Stock;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class StockFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            MagazineFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();  

        for ($i=0; $i<20; $i++){

            //Date aléatoire

            $date = new DateTimeImmutable();
            $randDate = $date->modify('-'. rand(1, 600) .' days');

            //Instancie l'entité avec laquelle travailler
            $stock = new Stock();
            $stock->setName($faker->name);
            $stock->setPlace($faker->city);
            $stock->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 years')));
            $stock->setMagazine($this->getReference("magazine_".  rand(0, 9)));

            // Met de côté les données en attente d'insertion
            $manager->persist($stock);
        }

        $manager->flush();
    }
}
