<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Pet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['Cat', 'Dog', 'Bird'] as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }

        $manager->flush();

        $categoryRepository = $manager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        for ($i = 1; $i <= 10; $i++) {
            $pet = new Pet();
            $pet->setName('Boule de Neige ' . $i);
            $pet->setCategory($categories[rand(0, count($categories) - 1)]);
            $pet->setBirthDate(new \DateTime('-' . $i . ' years'));
            $manager->persist($pet);
        }

        $manager->flush();
    }
}