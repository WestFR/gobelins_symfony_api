<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Children;
use App\Services\ScoreMailer\ScoreMailerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ChildrenController
 * @package App\Controller\SchoolClass
 */
class ChildrenController extends AbstractController
{

    /**
     * @SWG\Tag(name="Children")
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
     * @SWG\Parameter(
     *     name="X-AUTH-TOKEN",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="43fd8a51ae2758bb8176bff0c16",
     *     description="X-AUTH-TOKEN (api token authorization)"
     * )
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

        return $this->resSuccess($children, ['children_item', 'level_item', 'parent_item', 'actions_list']);
    }

    /**
     * @SWG\Tag(name="Children")
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

    /**
     * @SWG\Tag(name="Children")
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
     * @SWG\Response(
     *     response=200,
     *     description="Return actions list for children."
     * )
     *
     * @ParamConverter("action", converter="fos_rest.request_body")
     *
     * @param string $childrenId
     * @param Action $action
     * @param ConstraintViolationListInterface $violations
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postChildrenActionAction(string $childrenId, Action $action, ConstraintViolationListInterface $violations)
    {
        /** @var ScoreMailerService $scoreMailer */
        $scoreMailer = $this->container->get('mailer.score');

        /** @var Children $children */
        $children = $this->getDoctrine()->getRepository(Children::class)->find($childrenId);
        if (is_null($children)) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'Children not found');
        }

        $teacher = $children->getSchoolClass()->getTeacher();
        if ($this->getMe()->getId() !== $teacher->getId()) {
            return $this->resError(Response::HTTP_UNAUTHORIZED, 'You are not the teacher of the children');
        }

        if (count($violations) > 0) {
            return $this->resError(Response::HTTP_BAD_REQUEST, $violations);
        }

        $scoreMailer->setChildrenBeforeUpdate($children);
        $children->addAction($action);
        $scoreMailer->setChildrenAfterUpdate($children);
        $scoreMailer->checkScore();

        $this->getDoctrine()->getManager()->persist($children);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($children);
    }
}
