<?php

namespace App\DataFixtures\Factories;

use App\Entity\SchoolClass;
use Faker\Generator;

/**
 * Class SchoolClassFixtureFactory
 * @package App\DataFixtures\Factories
 */
class SchoolClassFixtureFactory extends FixtureFactory
{

    /**
     * @param Generator $generator
     * @return SchoolClass
     */
    public static function build(Generator $generator)
    {
        $schoolClass = new SchoolClass();
        $year = (int)$generator->year;
        $schoolClass->setYearStart($year);
        $schoolClass->setYearEnd($year + 1);
        return $schoolClass;
    }
}