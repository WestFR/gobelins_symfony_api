<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\DeserializationContext;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use FOS\RestBundle\Controller\Annotations as Rest;

use App\Entity\User;
use App\Entity\UserParent;
use App\Entity\Children;

/**
 * Class UserParentController
 * @package App\Controller
 */
class UserParentController extends AbstractController
{

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return the children(s) of authenticated user.",
     *      schema=@SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=UserParent::class, groups={"parent_list"}))
     *     )
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
     * @SWG\Tag(name="Parent")
     * @param string $parentId
     * @return JsonResponse
     */
    public function getParentsChildrensAction(string $parentId)
    {
        /** @var UserParent $parent */
        $parent = $this->getDoctrine()->getRepository(User::class)->find($parentId);

        if ($parent == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Parent id not found');
        }

        if (!$parent instanceof UserParent) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'User (parent) not found, this user is not a parent type.');
        }

        if ($parent->getApiToken() !== $this->getToken()) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'User not found, token is wrong or user is already logout.');
        }

        return $this->resSuccess($parent->getChildrens(), ['parent_list']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return the specified children of authenticated user.",
     *      schema=@SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=UserParent::class, groups={"parent_list"}))
     *     )
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
     * @SWG\Tag(name="Parent")
     *
     * @param $parentId
     * @param $childrenId
     * @return JsonResponse
     */
    public function getParentsChildrenAction(string $parentId, string $childrenId)
    {
        /** @var UserParent $parent */
        $parent = $this->getDoctrine()->getRepository(User::class)->find($parentId);

        if ($parent == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Parent id not found');
        }

        if (!$parent instanceof UserParent) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'User (parent) not found, this user is not a parent type.');
        }

        if ($parent->getApiToken() !== $this->getToken()) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'User not found, token is wrong or user is already logout.');
        }

        return $this->resSuccess($parent->getChildren($childrenId), ['parent_list']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Create the specified children of authenticated user.",
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
     *     description="JSON Payload for create a user.",
     *     required=true,
     *     format="application/json",
     *     schema=@SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Children::class, groups={"children_create"}))
     *     )
     * )
     *
     * @SWG\Tag(name="Parent")
     *
     * @ParamConverter("children", converter="fos_rest.request_body", options={"groups"={"children_create"}})
     *
     * @param string $parentId
     * @param Children $children
     * @param ConstraintViolationListInterface $violations
     * @return JsonResponse
     */
    public function postParentsChildrenAction(string $parentId, Children $children, ConstraintViolationListInterface $violations)
    {
        /** @var UserParent $parent */
        $parent = $this->getDoctrine()->getRepository(User::class)->find($parentId);

        if ($parent == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Parent id not found');
        }

        if (!$parent instanceof UserParent) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'User (parent) not found, this user is not a parent type.');
        }

        if ($parent->getApiToken() !== $this->getToken()) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'User not found, token is wrong or user is already logout.');
        }

        if ($violations->count() > 0) {
            return $this->resError(Response::HTTP_BAD_REQUEST, $violations);
        }

        $parent->addChildren($children);

        $em = $this->getDoctrine()->getManager();
        $em->persist($parent);
        $em->flush();

        return $this->resSuccess($children, [],Response::HTTP_OK, 'Children are added to current user.');
    }


    /**
     * @SWG\Response(
     *     response=200,
     *     description="Update the specified children of authenticated user."
     *     )
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
     *     description="JSON Payload for create a user.",
     *     required=true,
     *     format="application/json",
     *     schema=@SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Children::class, groups={"children_create"}))
     *     )
     * )
     *
     * @SWG\Tag(name="Parent")
     *
     * @ParamConverter("updatedChildren", converter="fos_rest.request_body", options={"groups"={"children_create"}})
     *
     * @param string $parentId
     * @param string $childrenId
     * @param Children $updatedChildren
     * @param ConstraintViolationListInterface $violations
     * @return JsonResponse
     */
    public function putParentsChildrenAction(string $parentId, string $childrenId, Children $updatedChildren, ConstraintViolationListInterface $violations)
    {
        $parent = $this->getDoctrine()->getRepository(User::class)->find($parentId);

        if ($parent == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Parent id not found');
        }

        if (!$parent instanceof UserParent) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'User (parent) not found, this user is not a parent type.');
        }

        if ($parent->getApiToken() !== $this->getToken()) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'User not found, token is wrong or user is already logout.');
        }

        if ($violations->count() > 0) {
            return $this->resError(Response::HTTP_BAD_REQUEST, $violations);
        }

        $children = $this->getDoctrine()->getRepository(Children::class)->find($childrenId);

        if ($children == null) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'Children not found for this user, this childrenId is not valid.');
        }

        $children->update($updatedChildren);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($children);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Delete the specified children for authenticated user."
     * )
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
     * @SWG\Tag(name="Parent")
     *
     * @param string $parentId
     * @param string $childrenId
     * @return JsonResponse
     */
    public function deleteParentsChildrenAction(string $parentId, string $childrenId)
    {
        $parent = $this->getDoctrine()->getRepository(User::class)->find($parentId);

        if ($parent == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Parent id not found');
        }

        if (!$parent instanceof UserParent) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'User (parent) not found, this user is not a parent type.');
        }

        if ($parent->getApiToken() !== $this->getToken()) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'User not found, token is wrong or user is already logout.');
        }

        $children = $parent->getChildren($childrenId);

        if ($children == null) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'Children not found for this user, this childrenId is not valid.');
        }

        $this->getDoctrine()->getManager()->remove($children);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess("", [], Response::HTTP_OK, 'Children are removed.');
    }

}