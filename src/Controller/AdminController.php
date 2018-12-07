<?php

namespace App\Controller;

use App\Entity\Action;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Swagger\Annotations as SWG;
use App\Entity\User;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return all users."
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
     * @SWG\Tag(name="Admin")
     *
     */
    public function getAdminUsersAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->resSuccess($users, ['user_create', 'user_admin']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Add specified user to admin users."
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
     * @SWG\Tag(name="Admin")
     *
     * @deprecated
     * @param string $userId
     * @return JsonResponse
     */
    public function putAdminAction(string $userId)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        if ($user == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'User not found');
        }
        if ($user->getId() != $this->getMe()->getId()) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, '');
        }

        $user->setAdminRole();
        $user->update($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($user, [], Response::HTTP_OK, 'User role are updated to "ROLE_ADMIN".');
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Remove authenticated admin user to admin users."
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
     * @SWG\Tag(name="Admin")
     *
     * @param string $userId
     * @return JsonResponse
     */
    public function deleteAdminAction(string $userId)
    {
        $user = $this->getMe();

        if ($user->getId() !== $userId) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'You are not this user');
        }

        $user->setBasicRole();
        $user->update($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess('', [], Response::HTTP_OK, 'User role are updated to "ROLE_USER".');
    }
}