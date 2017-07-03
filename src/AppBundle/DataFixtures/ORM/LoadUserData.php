<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userNames = [
            'John Smith',
            'Carol Bower',
            'Emily Glover',
        ];

        foreach ($userNames as $name) {
            $user = new User();
            $user->setName($name);

            $manager->persist($user);
        }

        $manager->flush();
    }
}