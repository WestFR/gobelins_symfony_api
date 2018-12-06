<?php

namespace App\Controller;

use App\Entity\UserTeacher;
use App\Entity\Action;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Swagger\Annotations as SWG;

/**
 * Class ActionController
 * @package App\Controller
 */
class ActionController extends AbstractController {

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return a collection of actions (all actions)."
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
     * @SWG\Tag(name="Action")
     *
     */
    public function getActionsAction()
    {
        $actions = $this->getDoctrine()->getRepository(Action::class)->findAll();

        return $this->resSuccess($actions, ['action_list']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return one specified action."
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
     * @SWG\Tag(name="Action")
     *
     * @param string $actionId
     * @return JsonResponse|Response
     */
    public function getActionAction(string $actionId)
    {
        $action = $this->getDoctrine()->getRepository(Action::class)->find($actionId);

        if ($action == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Action not found');
        }

        return $this->resSuccess($action, ['action_item']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Create one specified action."
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
     *     description="JSON Payload for create a action.",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *          @SWG\Property(property="label", type="string"),
     *          @SWG\Property(property="score", type="string"),
     *          @SWG\Property(property="type", type="action or action_custom"),
     *     )
     * )
     *
     * @SWG\Tag(name="Action")
     *
     * @ParamConverter("action", converter="fos_rest.request_body")
     *
     * @param Action $action
     * @param ConstraintViolationListInterface $violations
     * @return JsonResponse
     */
    public function postActionAction(Action $action, ConstraintViolationListInterface $violations)
    {
        /** @var EncoderFactoryInterface $encoderFactory */
        $encoderFactory = $this->container->get('security.encoder_factory');

        $creator = $this->getMe();
        if ($creator instanceof UserTeacher) {
            $action->setType(Action::TYPE_USER);
        } else if (in_array('ROLE_ADMIN', $creator->getRoles())) {
            $action->setType(Action::TYPE_ADMIN);
        } else {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'You are not a teacher or admin user');
        }

        if (count($violations) > 0) {
            return $this->resError(Response::HTTP_BAD_REQUEST, $violations);
        }

        $action->setCreator($creator);

        $em = $this->getDoctrine()->getManager();
        $em->persist($action);
        $em->flush();

        return $this->resSuccess($action, ['action_item'], Response::HTTP_CREATED);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Delete the specified action level."
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
     * @SWG\Tag(name="Action")
     *
     * @param string $actionId
     * @return JsonResponse
     */
    public function deleteActionAction(string $actionId)
    {
        /** @var Action $action */
        $action = $this->getDoctrine()->getRepository(Action::class)->find($actionId);

        if ($action == null) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Action are not found, wrong id or already delete.');
        }

        if ($this->getMe()->getId() != $action->getCreator()->getId() && $action->getType() == Action::TYPE_USER) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'You are not the creator of the action');
        }

        $this->getDoctrine()->getManager()->remove($action);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess('', [], Response::HTTP_OK, 'Action are removed.');
    }

}