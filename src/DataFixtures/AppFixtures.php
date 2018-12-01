<?php

namespace App\DataFixtures;

use App\DataFixtures\Factories\ActionFixtureFactory;
use App\DataFixtures\Factories\ChildrenFixtureFactory;
use App\DataFixtures\Factories\ParentFixtureFactory;
use App\DataFixtures\Factories\SchoolClassFixtureFactory;
use App\DataFixtures\Factories\TeacherFixtureFactory;
use App\Entity\ActionCustom;
use App\Entity\Children;
use App\Entity\SchoolClass;
use App\Entity\SchoolLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadSchool($manager);
    }

    private function loadSchool(ObjectManager $manager)
    {
        $schoolLevels = [];
        foreach (
            ['CP', 'CE1', 'CE2', 'CM1', 'CM2']
            as $level
        ) {
            $schoolLevel = new SchoolLevel();
            $schoolLevel->setLabel($level);
            $manager->persist($schoolLevel);
            $schoolLevels[] = $schoolLevel;
        }

        foreach ($schoolLevels as $schoolLevel) {
            $this->loadClass($manager, $schoolLevel);
        }
    }

    /**
     * @param ObjectManager $manager
     * @param SchoolLevel $schoolLevel
     */
    private function loadClass(ObjectManager $manager, SchoolLevel $schoolLevel)
    {
        $teacher = TeacherFixtureFactory::buildItem($manager);

        SchoolClassFixtureFactory::buildItem(
            $manager,
            function ($schoolClass) use ($manager, $teacher, $schoolLevel) {

                /** @var SchoolClass $schoolClass */
                $schoolClass->setTeacher($teacher);
                $schoolClass->setSchoolLevel($schoolLevel);

                $childrens = ChildrenFixtureFactory::buildArray(
                    $manager,
                    20,
                    function ($children) use ($manager, $teacher, $schoolClass) {

                        /** @var Children $children */
                        $children->setParent(ParentFixtureFactory::buildItem($manager));
                        $children->setSchoolClass($schoolClass);
                        $children->addAction(ActionFixtureFactory::buildItem($manager, function ($action) use ($teacher) {
                            /** @var ActionCustom $action */
                            $action->setCreator($teacher);
                        }));

                    }
                );

            }
        );

        $manager->flush();
    }
}
