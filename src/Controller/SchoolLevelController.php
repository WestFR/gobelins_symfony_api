<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

use FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

use App\Entity\SchoolLevel;

/**
 * Class SchoolLevelController
 * @package App\Controller
 */
class SchoolLevelController extends AbstractController {

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return all schools level."
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
     * @SWG\Tag(name="SchoolLevel")
     *
     */
    public function getLevelsAction()
    {
        $schoolLevels = $this->getDoctrine()->getRepository(SchoolLevel::class)->findAll();

        if ($schoolLevels == null) {
            // Todo: Use response parent method
            $data = array('code' => Response::HTTP_OK, 'message' => 'No school level for this moment, create one before !');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        return $this->resSuccess($schoolLevels, ['school_name']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return one specified school level."
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
     * @SWG\Tag(name="SchoolLevel")
     *
     * @param string $label
     * @return JsonResponse|Response
     */
    public function getLevelAction(string $label) {
        $schoolLevel = $this->getDoctrine()->getRepository(SchoolLevel::class)->find($label);

        if ($schoolLevel == null) {
            // Todo: Use response parent method
            $data = array('code' => Response::HTTP_OK, 'message' => 'No school level for this school name, create this before !');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        return $this->resSuccess($schoolLevel, ['school_all']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Create one specified school level."
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
     *     description="JSON Payload for create a school level.",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *          @SWG\Property(property="label", type="string"),
     *     )
     * )
     *
     * @SWG\Tag(name="SchoolLevel")
     *
     * @ParamConverter("schoolLevel", converter="fos_rest.request_body")
     *
     * @param SchoolLevel $schoolLevel
     * @param ConstraintViolationListInterface $violations
     * @return JsonResponse
     */
    public function postLevelAction(SchoolLevel $schoolLevel, ConstraintViolationListInterface $violations) {
        if ($violations->count() > 0) {
            return $this->resError(Response::HTTP_BAD_REQUEST, $violations);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($schoolLevel);
        $em->flush();

        // Todo: Use response parent method
        $data = array('code' => Response::HTTP_OK, 'message' => 'School level are created.');
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Updated the specified school level."
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
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload for create a school level.",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *          @SWG\Property(property="label", type="string"),
     *     )
     * )
     *
     * @SWG\Tag(name="SchoolLevel")
     *
     * @ParamConverter("updatedLevel", converter="fos_rest.request_body")
     *
     * @param string $label
     * @param SchoolLevel $updatedLevel
     * @param ConstraintViolationListInterface $violations
     * @return JsonResponse
     */
    public function putLevelAction(string $label, SchoolLevel $updatedLevel, ConstraintViolationListInterface $violations)
    {
        $level = $this->getDoctrine()->getRepository(SchoolLevel::class)->find($label);

        if (is_null($level)) {
            return $this->resError(Response::HTTP_BAD_REQUEST, 'School level not found');
        }

        if ($violations->count() > 0) {
            return $this->resError(Response::HTTP_BAD_REQUEST, $violations);
        }

        $level->update($updatedLevel);
        $this->getDoctrine()->getManager()->flush();

        return $this->resSuccess($level, [], Response::HTTP_OK, 'School level are updated.');
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Delete the specified school level."
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
     * @SWG\Tag(name="SchoolLevel")
     *
     * @param string $label
     * @return JsonResponse
     */
    public function deleteLevelAction(string $label)
    {
        $schoolToRemove = $this->getDoctrine()->getRepository(SchoolLevel::class)->findOneBy(['label' => $label]);

        if ($schoolToRemove == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'School level are not found, wrong name or already delete.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $this->getDoctrine()->getManager()->remove($schoolToRemove);
        $this->getDoctrine()->getManager()->flush();

        // Todo: Use response parent method
        $data = array('code' => Response::HTTP_OK, 'message' => 'School level are removed.');
        return new JsonResponse($data, Response::HTTP_OK);
    }



}