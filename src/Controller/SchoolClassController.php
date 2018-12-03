<?php

namespace App\Controller;

use App\Entity\Children;
use App\Entity\SchoolClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class SchoolClassController
 * @package App\Controller\SchoolClass
 */
class SchoolClassController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getClassesAction()
    {
        $classes = $this->getDoctrine()->getRepository(SchoolClass::class)->findAll();

        return $this->sendJson($classes, ['classes_list']);
    }

    /**
     * @param int $classId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getClassAction(int $classId)
    {
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);

        return $this->sendJson($class, ['classes_item']);
    }

    /**
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
        if (count($violations) > 0) return $this->sendJson($violations, [], Response::HTTP_BAD_REQUEST);

        /** @var SchoolClass $class */
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);
        $class->addChildren($children);

        $this->getDoctrine()->getManager()->persist($class);
        $this->getDoctrine()->getManager()->flush();

        return $this->sendJson($class, ['class_item'], Response::HTTP_CREATED);
    }

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

        $this->sendJson($class, ['class_item']);
    }
}