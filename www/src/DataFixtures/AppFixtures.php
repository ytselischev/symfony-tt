<?php

namespace App\DataFixtures;

use App\Entity\Desk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < Desk::TOTAL; $i++) {
            $table = new Desk();
            $manager->persist($table);
        }

        $manager->flush();
    }
}
