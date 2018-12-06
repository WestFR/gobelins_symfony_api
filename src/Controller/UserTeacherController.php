<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\SchoolClass;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

use App\Entity\UserTeacher;

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

        return $this->resSuccess($teachers, ['user_list', 'teacher_list']);
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

        return $this->resSuccess($teacher, ['user_item', 'teacher_item']);
    }

    /**
     * @SWG\Tag(name="Teacher")
     *
     * @ParamConverter("class", converter="fos_rest.request_body")
     *
     * @SWG\Response(
     *     response="201",
     *     description="Return created teacher item",
     *     schema=@SWG\Schema(
     *          type="object",
     *          ref=@Model(type=UserTeacher::class, groups={"user_item", "teacher_item"})
     *     )
     * )
     *
     * @param int $teacherId
     * @param SchoolClass $class
     * @param ConstraintViolationListInterface $violations
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postTeacherClassAction(
        int $teacherId,
        SchoolClass $class,
        ConstraintViolationListInterface $violations
    ) {
        if (count($violations) > 0) return $this->resSuccess($violations, [], Response::HTTP_BAD_REQUEST);

        /** @var UserTeacher $teacher */
        $teacher = $this->getDoctrine()->getRepository(UserTeacher::class)->find($teacherId);
        $teacher->addSchoolClass($class);

        $this->getDoctrine()->getManager()->persist($teacher);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($teacher, ['teacher_item'], Response::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="Teacher")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Delete item success message"
     * )
     *
     * @param int $teacherId
     * @param int $classId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteTeacherClassAction(int $teacherId, int $classId)
    {
        /** @var UserTeacher $teacher */
        $teacher = $this->getDoctrine()->getRepository(UserTeacher::class)->find($teacherId);
        /** @var SchoolClass $class */
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);

        $teacher->removeSchoolClass($class);

        $this->getDoctrine()->getManager()->persist($teacher);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($teacher, ['teacher_item'], Response::HTTP_CREATED);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Create one specified action."
     * ),
     *
     * @SWG\Parameter(
     *     name="X-AUTH-TOKEN",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="43fd8a51ae2758bb8176bff0c16",
     *     description="X-AUTH-TOKEN (api token authorization)"
     * )
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload for create a action.",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *          @SWG\Property(property="label", type="string"),
     *          @SWG\Property(property="score", type="string"),
     *          @SWG\Property(property="type", type="action or action_custom"),
     *     )
     * )
     *
     * @SWG\Tag(name="Teacher")
     *
     * @ParamConverter("action", converter="fos_rest.request_body")
     *
     * @param string $teacherId
     * @param Action $action
     * @param ConstraintViolationListInterface $violations
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postTeacherActionAction(string $teacherId, Action $action, ConstraintViolationListInterface $violations)
    {
        /** @var UserTeacher $teacher */
        $teacher = $this->getDoctrine()->getRepository(UserTeacher::class)->find($teacherId);

        if (is_null($teacher)) return $this->resError(Response::HTTP_BAD_REQUEST, 'Unknown teacher id');
        if (count($violations)) return $this->resError(Response::HTTP_BAD_REQUEST, $violations);

        $teacher->addAction($action);

        $this->getDoctrine()->getManager()->persist($teacher);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($action, ['action_item']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Create one specified action."
     * ),
     *
     * @SWG\Parameter(
     *     name="X-AUTH-TOKEN",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="43fd8a51ae2758bb8176bff0c16",
     *     description="X-AUTH-TOKEN (api token authorization)"
     * )
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload for create a action.",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *          @SWG\Property(property="label", type="string"),
     *          @SWG\Property(property="score", type="string"),
     *          @SWG\Property(property="type", type="action or action_custom"),
     *     )
     * )
     *
     * @SWG\Tag(name="Teacher")
     *
     * @ParamConverter("action", converter="fos_rest.request_body")
     *
     * @deprecated
     *
     * @param $teacherId
     * @param Action $action
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function putTeacherActionAction($teacherId, Action $action)
    {
        return $this->resError(Response::HTTP_NOT_IMPLEMENTED, 'This route is not available for this moment.');
    }
}