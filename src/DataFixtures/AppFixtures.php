<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Couplet;
use App\Entity\Hymn;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
    }

    public function load(ObjectManager $manager): void
    {
        $hymnId = 1;

        for ($i = 1; $i <= 20; $i++) {
            $category = new Category();
            $category->setCategoryId(sprintf('category_%d', $i));
            $category->setTitle(sprintf('%s %d', $this->faker->realText(10), $i));
            $manager->persist($category);

            for ($j = 1; $j <= random_int(4, 7); $j++) {
                $hymn = new Hymn();
                $hymn->setHymnId($hymnId);
                $hymn->setCategory($category);
                $hymn->setTitle($this->faker->realText(20));
                $manager->persist($hymn);
                $hymnId++;

                for ($k = 1; $k <= random_int(2, 5); $k++) {
                    $isChorus = random_int(0, 1);
                    $text = $this->faker->realText(250);
                    $coupletString = $isChorus ? sprintf('Припев %d', $k) : sprintf('Куплет %d', $k);
                    $coupletString = sprintf('%s: %s', $coupletString, $text);

                    $couplet = new Couplet();
                    $couplet->setHymn($hymn);
                    $couplet->setCouplet($coupletString);
                    $couplet->setPosition($k);
                    $couplet->setIsChorus($isChorus);
                    $manager->persist($couplet);
                }
            }
        }

        $manager->flush();
    }
}
