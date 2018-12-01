<?php


namespace App\DataFixtures\Factories;


use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class FixtureFactory
 * @package App\DataFixtures\Factories
 */
abstract class FixtureFactory
{

    /**
     * @param ObjectManager $manager
     * @param callable|null $onBuild
     * @return mixed
     */
    public static function buildItem(ObjectManager $manager, callable $onBuild = null)
    {
        $generator = Factory::create('fr_FR');
        $item = static::build($generator);
        if (!is_null($onBuild)) {
            $onBuild($item);
        }
        $manager->persist($item);
        return $item;
    }

    /**
     * @param ObjectManager $manager
     * @param int $size
     * @param callable|null $onBuild
     * @return array
     */
    public static function buildArray(ObjectManager $manager, int $size, callable $onBuild = null)
    {
        $generator = Factory::create('fr_FR');
        $items = [];
        for ($i = 0; $i < $size; $i++) {
            $items[$i] = static::build($generator);
            if (!is_null($onBuild)) {
                $onBuild($items[$i]);
            }
            $manager->persist($items[$i]);
        }
        return $items;
    }

    /**
     * @param Generator $generator
     * @return mixed
     */
    abstract public static function build(Generator $generator);
}