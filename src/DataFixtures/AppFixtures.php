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
        $hymnId = 0;

        for ($i = 1; $i <= 20; $i++) {
            $category = new Category();
            $categoryId = sprintf('category_%d', $i);
            $categoryTitle = sprintf('%s %d', $this->faker->realText(10), $i);
            $category->setCategoryId($categoryId);
            $category->setTitle($categoryTitle);
            $manager->persist($category);

//            $this->elasticSearch->delete([
//                'index' => 'categories',
//                'id'    => $categoryId,
//            ]);

            $this->elasticSearch->index([
                'index' => 'categories',
                'id'    => $categoryId,
                'body'  => ['categoryTitle' => $categoryTitle]
            ]);

            for ($j = 1; $j <= random_int(4, 7); $j++) {
                $hymnId++;
                $firstCouplet = $this->faker->realText(250);

                $hymn = new Hymn();
                $hymn->setHymnId($hymnId);
                $hymn->setCategory($category);
                $hymnTitle = mb_substr($firstCouplet, 0, 30);
                $hymn->setTitle($hymnTitle);
                $manager->persist($hymn);

//                $this->elasticSearch->delete([
//                    'index' => 'hymns',
//                    'id'    => $hymnId,
//                ]);

                $this->elasticSearch->index([
                    'index' => 'hymns',
                    'id'    => $hymnId,
                    'body'  => [
                        'hymnId'        => $hymnId,
                        'categoryTitle' => $categoryTitle,
                        'hymnTitle'         => $hymnTitle,
                    ]
                ]);

                for ($position = 1; $position <= random_int(2, 5); $position++) {
                    $isChorus = random_int(0, 1);
                    $coupletText = $position === 1 ? $firstCouplet : $this->faker->realText(250);
                    $coupletString = $isChorus ? sprintf('Припев %d', $position) : sprintf('Куплет %d', $position);
                    $coupletString = sprintf('%s: %s', $coupletString, $coupletText);

                    $couplet = new Couplet();
                    $couplet->setHymn($hymn);
                    $couplet->setCouplet($coupletString);
                    $couplet->setPosition($position);
                    $couplet->setIsChorus($isChorus);
                    $manager->persist($couplet);

                    $coupletId = sprintf('%s_%s', $hymnId, $position);

//                    $this->elasticSearch->delete([
//                        'index' => 'couplets',
//                        'id'    => $coupletId,
//                    ]);

                    $this->elasticSearch->index([
                        'index' => 'couplets',
                        'id'    => $coupletId,
                        'body'  => [
                            'hymnId'        => $hymnId,
                            'categoryTitle' => $categoryTitle,
                            'hymnTitle'     => $hymnTitle,
                            'couplet'       => $coupletString,
                        ]
                    ]);
                }
            }
        }

        $manager->flush();
    }
}
