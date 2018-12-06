<?php

namespace App\Controller;

use App\Entity\Children;
use App\Entity\SchoolClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Swagger\Annotations as SWG;

/**
 * Class SchoolClassController
 * @package App\Controller\SchoolClass
 */
class SchoolClassController extends AbstractController
{
    /**
     * @SWG\Tag(name="Class")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return classes list."
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getClassesAction()
    {
        $classes = $this->getDoctrine()->getRepository(SchoolClass::class)->findAll();

        return $this->resSuccess($classes, ['classes_list']);
    }

    /**
     * @SWG\Tag(name="Class")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return class item."
     * )
     *
     * @param int $classId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getClassAction(int $classId)
    {
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);

        return $this->resSuccess($class, ['classes_item']);
    }

    /**
     * @SWG\Tag(name="Class")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Add children in class."
     * )
     *
     * @ParamConverter("children", converter="fos_rest.request_body")
     *
     * @param int $classId
     * @param Children $children
     * @param ConstraintViolationListInterface $violations
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postClassChildrenAction(
        int $classId,
        Children $children,
        ConstraintViolationListInterface $violations
    ) {
        if (count($violations) > 0) return $this->resSuccess($violations, [], Response::HTTP_BAD_REQUEST);

        /** @var SchoolClass $class */
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);
        $class->addChildren($children);

        $this->getDoctrine()->getManager()->persist($class);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($class, ['class_item'], Response::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="Class")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete children in class."
     * )
     *
     * @param int $classId
     * @param int $childrenId
     */
    public function deleteClassChildrenAction(
        int $classId,
        int $childrenId
    ) {
        /** @var SchoolClass $class */
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);
        /** @var Children $children */
        $children = $this->getDoctrine()->getRepository(SchoolClass::class)->find($childrenId);

        $class->removeChildren($children);

        $this->getDoctrine()->getManager()->persist($class);
        $this->getDoctrine()->getManager()->flush();

        $this->resSuccess($class, ['class_item']);
    }

    /**
     * @SWG\Tag(name="Class")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return childrens in class."
     * )
     *
     * @param int $classId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getClassChildrensAction(int $classId)
    {
        /** @var SchoolClass $class */
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);

        return $this->resSuccess($class->getChildrens(), ['childrens_list']);
    }
}