<?php

namespace App\DataFixtures\Factories;

use App\Entity\ActionCustom;
use Faker\Generator;

/**
 * Class ActionFixtureFactory
 * @package App\DataFixtures\Factories
 */
class ActionFixtureFactory extends FixtureFactory
{

    /**
     * @param Generator $generator
     * @return ActionCustom
     */
    public static function build(Generator $generator)
    {
        $action = new ActionCustom();
        $action->setLabel($generator->sentence());
        $action->setScore($generator->numberBetween(-5, 5));
        return $action;
    }
}