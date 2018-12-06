<?php

namespace App\DataFixtures\Factories;

use App\Entity\Action;
use Faker\Generator;

/**
 * Class ActionFixtureFactory
 * @package App\DataFixtures\Factories
 */
class ActionFixtureFactory extends FixtureFactory
{

    /**
     * @param Generator $generator
     * @return Action
     */
    public static function build(Generator $generator)
    {
        $action = new Action();
        $action->setLabel($generator->sentence());
        $action->setScore($generator->numberBetween(-5, 5));
        return $action;
    }
}