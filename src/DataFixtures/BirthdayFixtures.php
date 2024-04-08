<?php

namespace App\DataFixtures;

use App\Factory\BirthdayFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BirthdayFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        BirthdayFactory::new()->createMany(20);

        $manager->flush();
    }
}
