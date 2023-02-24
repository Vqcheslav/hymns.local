<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Couplet;
use App\Entity\Hymn;
use App\Services\ElasticService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Elastic\Elasticsearch\Client;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    private Client $elasticSearch;

    public function __construct(ElasticService $elasticService)
    {
        $this->faker = Factory::create('ru_RU');
        $this->elasticSearch = $elasticService->getElastic();
    }

    public function load(ObjectManager $manager): void
    {
        $hymnId = 1;

        for ($i = 1; $i <= 20; $i++) {
            $category = new Category();
            $categoryId = sprintf('category_%d', $i);
            $categoryTitle = sprintf('%s %d', $this->faker->realText(10), $i);
            $category->setCategoryId($categoryId);
            $category->setTitle($categoryTitle);
            $manager->persist($category);

            $this->elasticSearch->index([
                'index' => 'categories',
                'id'    => $categoryId,
                'body'  => ['categoryTitle' => $categoryTitle]
            ]);

            for ($j = 1; $j <= random_int(4, 7); $j++) {
                $hymn = new Hymn();
                $hymn->setHymnId($hymnId);
                $hymn->setCategory($category);
                $hymnTitle = $this->faker->realText(20);
                $hymn->setTitle($hymnTitle);
                $manager->persist($hymn);
                $hymnId++;

                $this->elasticSearch->index([
                    'index' => 'hymns',
                    'id'    => $hymnId,
                    'body'  => [
                        'hymnId' => $hymnId,
                        'categoryTitle' => $categoryTitle,
                        'title' => $hymnTitle,
                    ]
                ]);

                for ($k = 1; $k <= random_int(2, 5); $k++) {
                    $isChorus = random_int(0, 1);
                    $coupletText = $this->faker->realText(250);
                    $coupletString = $isChorus ? sprintf('Припев %d', $k) : sprintf('Куплет %d', $k);
                    $coupletString = sprintf('%s: %s', $coupletString, $coupletText);

                    $couplet = new Couplet();
                    $couplet->setHymn($hymn);
                    $couplet->setCouplet($coupletString);
                    $couplet->setPosition($k);
                    $couplet->setIsChorus($isChorus);
                    $manager->persist($couplet);

                    $this->elasticSearch->index([
                        'index' => 'couplets',
                        'body'  => [
                            'hymnId' => $hymnId,
                            'categoryTitle' => $categoryTitle,
                            'hymnTitle' => $hymnTitle,
                            'couplet' => $coupletString,
                        ]
                    ]);
                }
            }
        }

        $manager->flush();
    }
}
