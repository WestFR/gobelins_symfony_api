<?php

namespace App\DataFixtures\Factories;

use App\Entity\UserTeacher;
use Faker\Generator;

/**
 * Class TeacherFixtureFactory
 * @package App\DataFixtures
 */
class TeacherFixtureFactory extends FixtureFactory
{

    /**
     * @param Generator $generator
     * @return UserTeacher
     */
    public static function build(Generator $generator)
    {
        $teacher = new UserTeacher();
        $teacher->setFirstname($generator->firstName);
        $teacher->setLastname($generator->lastName);
        $teacher->setPassword($generator->password);
        $teacher->setMail($generator->email);
        $teacher->setPhone($generator->phoneNumber);
        $teacher->setBornedAt(new \DateTime($generator->date()));
        $teacher->setCreatedAt($generator->dateTime());
        $teacher->setUpdatedAt(new \DateTime());
        $teacher->setBasicRole();
        return $teacher;
    }
}