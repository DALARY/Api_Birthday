<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Factory\BirthdayFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BirthdayFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::new()->createMany(5);
        
        // Récupérer tous les utilisateurs
        $users = $manager->getRepository(User::class)->findAll();

        // Créer des anniversaires pour chaque utilisateur
        foreach ($users as $user) {
            BirthdayFactory::new(['user' => $user])->createMany(5); // Crée 5 anniversaires pour chaque utilisateur
        }

        $manager->flush();
    }
}
