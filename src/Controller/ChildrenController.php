<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Children;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class ChildrenController
 * @package App\Controller\SchoolClass
 */
class ChildrenController extends AbstractController
{

    /**
     * @SWG\Tag(name="Children")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return list for children."
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getChildrensAction()
    {
        /** @var Children $children */
        $childrens = $this->getDoctrine()->getRepository(Children::class)->findAll();

        return $this->resSuccess($childrens, ['children_list', 'level_item']);
    }

    /**
     * @SWG\Tag(name="Children")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return children."
     * )
     *
     * @param string $childrenId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getChildrenAction(string $childrenId)
    {
        /** @var Children $children */
        $children = $this->getDoctrine()->getRepository(Children::class)->find($childrenId);

        if (is_null($children)) {
            return $this->resError(Response::HTTP_BAD_REQUEST, sprintf('Children %s not found', $childrenId));
        }

        dd($this->getDoctrine()->getRepository(Action::class)->getScoreByChildren($childrenId));

        return $this->resSuccess($children, ['children_item', 'level_item', 'parent_item', 'actions_list']);
    }

    /**
     * @SWG\Tag(name="Children")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return actions list for children."
     * )
     *
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