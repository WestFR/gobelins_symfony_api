<?php

namespace App\Controller\SchoolClass;

use App\Controller\AbstractController;
use App\Entity\SchoolClass;

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
}