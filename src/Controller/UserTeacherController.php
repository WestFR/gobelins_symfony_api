<?php

namespace App\Controller;

use App\Entity\SchoolClass;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use App\Entity\UserTeacher;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserTeacherController extends AbstractController
{
    /**
     * @SWG\Tag(name="Teacher")
     * @SWG\Response(
     *     response="200",
     *     description="Return a collection of teachers",
     *     schema=@SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=UserTeacher::class, groups={"user_list", "teacher_list"}))
     *     )
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTeachersAction()
    {
        $teachers = $this->getDoctrine()->getRepository(UserTeacher::class)->findAll();

        return $this->sendJson($teachers, ['user_list', 'teacher_list']);
    }

    /**
     * @SWG\Tag(name="Teacher")
     * @SWG\Response(
     *     response="200",
     *     description="Return a teacher item",
     *     schema=@SWG\Schema(
     *          type="object",
     *          ref=@Model(type=UserTeacher::class, groups={"user_item", "teacher_item"})
     *     )
     * )
     *
     * @param int $teacherId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTeacherAction(int $teacherId)
    {
        $teacher = $this->getDoctrine()->getRepository(UserTeacher::class)->find($teacherId);

        return $this->sendJson($teacher, ['user_item', 'teacher_item']);
    }

    /**
     * @param int $teacherId
     * @param SchoolClass $class
     * @param ConstraintViolationListInterface $violations
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postTeacherClassesAction(
        int $teacherId,
        SchoolClass $class,
        ConstraintViolationListInterface $violations
    ) {
        /** @var UserTeacher $teacher */
        $teacher = $this->getDoctrine()->getRepository(UserTeacher::class)->find($teacherId);
        $teacher->addSchoolClass($class);
        $this->getDoctrine()->getManager()->persist($teacher);
        $this->getDoctrine()->getManager()->flush();

        return $this->sendJson($teacher, ['teacher_item']);
    }
}