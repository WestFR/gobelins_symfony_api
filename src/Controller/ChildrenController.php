<?php

namespace App\Controller;

use App\Entity\SchoolClass;

/**
 * Class ChildrenController
 * @package App\Controller\SchoolClass
 */
class ChildrenController extends AbstractController
{
    /**
     * @param int $classId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getChildrens(int $classId)
    {
        /** @var SchoolClass $class */
        $class = $this->getDoctrine()->getRepository(SchoolClass::class)->find($classId);

        return $this->sendJson($class->getChildrens(), ['childrens_list']);
    }
}