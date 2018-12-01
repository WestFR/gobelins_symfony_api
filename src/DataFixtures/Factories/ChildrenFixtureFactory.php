<?php

namespace App\DataFixtures\Factories;

use App\Entity\Children;
use Faker\Generator;

/**
 * Class ChildrenFixtureFactory
 * @package App\DataFixtures\Factories
 */
class ChildrenFixtureFactory extends FixtureFactory
{

    /**
     * @param Generator $generator
     * @return Children|mixed
     */
    public static function build(Generator $generator)
    {
        $children = new Children();
        $children->setFirstname($generator->firstName);
        $children->setLastname($generator->lastName);
        $children->setBornedAt($generator->dateTime());
        return $children;
    }
}