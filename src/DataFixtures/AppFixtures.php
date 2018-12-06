<?php

namespace App\DataFixtures;

use App\DataFixtures\Factories\ActionFixtureFactory;
use App\DataFixtures\Factories\ChildrenFixtureFactory;
use App\DataFixtures\Factories\ParentFixtureFactory;
use App\DataFixtures\Factories\SchoolClassFixtureFactory;
use App\DataFixtures\Factories\TeacherFixtureFactory;
use App\Entity\Action;
use App\Entity\Children;
use App\Entity\SchoolClass;
use App\Entity\SchoolLevel;
use App\Entity\UserParent;
use App\Entity\UserTeacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * AppFixtures constructor.
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadSchool($manager);
    }

    /**
     * @param ObjectManager $manager
     */
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
        $encoderFactory = $this->encoderFactory;
        $teacher = TeacherFixtureFactory::buildItem($manager, function ($teacher) use ($encoderFactory) {
            /** @var UserTeacher $teacher */
            $teacher->setPassword($encoderFactory->getEncoder($teacher)->encodePassword('0000', null));
        });

        SchoolClassFixtureFactory::buildItem(
            $manager,
            function ($schoolClass) use ($manager, $teacher, $schoolLevel, $encoderFactory) {

                /** @var SchoolClass $schoolClass */
                $schoolClass->setTeacher($teacher);
                $schoolClass->setSchoolLevel($schoolLevel);

                $childrens = ChildrenFixtureFactory::buildArray(
                    $manager,
                    20,
                    function ($children) use ($manager, $teacher, $schoolClass, $encoderFactory) {

                        /** @var Children $children */
                        $children->setParent(ParentFixtureFactory::buildItem($manager, function ($parent) use ($encoderFactory) {
                            /** @var UserParent $parent */
                            $parent->setPassword($encoderFactory->getEncoder($parent)->encodePassword('0000', null));
                        }));
                        $children->setSchoolClass($schoolClass);

                        for ($i = 0; $i < 10; $i++) {
                            $children->addAction(ActionFixtureFactory::buildItem($manager, function ($action) use ($teacher) {
                                /** @var Action $action */
                                $action->setType(Action::TYPE_USER);
                                $action->setCreator($teacher);
                            }));
                        }

                    }
                );

            }
        );

        $manager->flush();
    }
}
