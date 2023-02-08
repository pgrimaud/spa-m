<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Pet;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setFirstName('John ' . $i);
            $user->setLastName('Doe');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password')
            );

            if ($i === 1) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $manager->persist($user);
        }

        $manager->flush();

        foreach (['Cat', 'Dog', 'Bird'] as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }

        $manager->flush();

        $categoryRepository = $manager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        for ($i = 1; $i <= 10; $i++) {
            $pet = new Pet();
            $pet->setName('Boule de Neige ' . $i);
            $pet->setCategory($categories[rand(0, count($categories) - 1)]);
            $pet->setUser($users[rand(0, count($users) - 1)]);
            $pet->setBirthDate(new \DateTime('-' . $i . ' years'));
            $manager->persist($pet);
        }

        $manager->flush();
    }
}