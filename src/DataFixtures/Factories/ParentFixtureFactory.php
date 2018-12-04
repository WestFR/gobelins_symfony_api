<?php

namespace App\DataFixtures\Factories;

use App\Entity\UserParent;
use Faker\Generator;

/**
 * Class ParentFixtureFactory
 * @package App\DataFixtures
 */
class ParentFixtureFactory extends FixtureFactory
{

    /**
     * @param Generator $generator
     * @return mixed
     * @internal param ObjectManager $manager
     */
    public static function build(Generator $generator)
    {
        $parent = new UserParent();
        $parent->setFirstname($generator->firstName);
        $parent->setLastname($generator->lastName);
        $parent->setPassword($generator->password);
        $parent->setMail($generator->email);
        $parent->setPhone($generator->phoneNumber);
        $parent->setBornedAt(new \DateTime($generator->date()));
        $parent->setCreatedAt($generator->dateTime());
        $parent->setUpdatedAt(new \DateTime());
        $parent->setBasicRole();
        return $parent;
    }
}