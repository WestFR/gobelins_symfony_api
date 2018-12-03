<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

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
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTeacherAction(int $id)
    {
        $teacher = $this->getDoctrine()->getRepository(UserTeacher::class)->find($id);

        return $this->sendJson($teacher, ['user_item', 'teacher_item']);
    }
}