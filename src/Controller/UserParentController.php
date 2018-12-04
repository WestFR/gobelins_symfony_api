<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use App\Entity\UserParent;

/**
 * Class UserParentController
 * @package App\Controller\UserParent
 */
class UserParentController extends AbstractController
{
    /**
     * @SWG\Tag(name="Parent")
     * @SWG\Response(
     *     response="200",
     *     description="Return a collection of parents",
     *     schema=@SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=UserParent::class, groups={"user_list", "parent_list"}))
     *     )
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getParentsAction()
    {
        $parents = $this->getDoctrine()->getRepository(UserParent::class)->findAll();

        return $this->resSuccess($parents, ['user_list', 'parent_list']);
    }

    /**
     * @SWG\Tag(name="Parent")
     * @SWG\Response(
     *     response="200",
     *     description="Return a parent item",
     *     schema=@SWG\Schema(
     *          type="object",
     *          ref=@Model(type=UserParent::class, groups={"user_item", "parent_item"})
     *     )
     * )
     *
     * @param string $parentId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getParentAction(string $parentId)
    {
        $parent = $this->getDoctrine()->getRepository(UserParent::class)->find($parentId);

        return $this->resSuccess($parent, ['user_item', 'parent_item']);
    }

}