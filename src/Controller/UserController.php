<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

use Swagger\Annotations as SWG;

use App\Entity\User;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController {

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return the authenticated user."
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
     * @SWG\Tag(name="User")
     *
     */
    public function getUsersMeAction()
    {
        $apiToken = $this->request->headers->get('X-AUTH-TOKEN');
        if ($apiToken == null) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'Missing arguments (see documentation)');
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        return $this->resSuccess($user, ['user_create']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Updated the authenticated user."
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
     * @SWG\Tag(name="User")
     *
     * @ParamConverter("updatedUser", converter="fos_rest.request_body", options={"groups"={"user_update"}})
     *
     * @param User $updatedUser
     * @param ConstraintViolationListInterface $violations
     * @return JsonResponse
     * @throws \Exception
     */
    public function putUsersMeAction(User $updatedUser, ConstraintViolationListInterface $violations)
    {
        /** @var EncoderFactoryInterface $encoderFactory */
        $encoderFactory = $this->container->get('security.encoder_factory');
        $apiToken = $this->request->headers->get('X-AUTH-TOKEN');

        if ($apiToken == null) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'Missing arguments (see documentation)');
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        if ($violations->count() > 0) {
            return $this->resError(Response::HTTP_BAD_REQUEST, $violations);
        }

        if ($updatedUser->getPassword() != null) {
            $encoder = $encoderFactory->getEncoder($updatedUser);
            $hashedPassword = $encoder->encodePassword($updatedUser->getPassword(), null);
            $updatedUser->setPassword($hashedPassword);
        }

        $updatedUser->setUpdatedAt(new \DateTime());

        $user->update($updatedUser);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($user, [], Response::HTTP_OK, 'User are updated.');
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Delete the authenticated user."
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
     * @SWG\Tag(name="User")
     */
    public function deleteUsersMeAction()
    {
        $apiToken = $this->request->headers->get('X-AUTH-TOKEN');
        $userToRemove = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        $this->getDoctrine()->getManager()->remove($userToRemove);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess(null, [], Response::HTTP_OK, 'User are removed.');
    }
}
