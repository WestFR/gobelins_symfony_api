<?php

namespace App\Controller;

use App\Entity\Children;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChildrenController
 * @package App\Controller\SchoolClass
 */
class ChildrenController extends AbstractController
{
    /**
     * @param string $childrenId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getChildrenActionsAction(string $childrenId)
    {
        /** @var Children $children */
        $children = $this->getDoctrine()->getRepository(Children::class)->find($childrenId);

        if (is_null($children)) {
            return $this->resError(Response::HTTP_BAD_REQUEST, sprintf('Children %s not found', $childrenId));
        }

        return $this->resSuccess($children->getActions(), ['actions_list']);
    }
}