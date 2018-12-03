<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

use FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

use App\Entity\SchoolLevel;

/**
 * @Route("/api/schools/levels", name="api_schoolLevels")
 */
class SchoolLevelController extends Controller {

    /**
     *
     * @Rest\Get("/")
     *
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
    public function getAll(SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();

        $schoolLevels = $this->getDoctrine()->getRepository(SchoolLevel::class)->findAll();

        if ($schoolLevels == null) {
            $data = array('code' => Response::HTTP_OK, 'message' => 'No school level for this moment, create one before !');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        return new Response($serializer->serialize($schoolLevels, 'json', $serializationContext->setGroups(['school_name'])),  Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Get("/{name}")
     *
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
     */
    public function getOne($name, SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();

        $schoolLevel = $this->getDoctrine()->getRepository(SchoolLevel::class)->findOneBy(['label' => $name]);

        if ($schoolLevel == null) {
            $data = array('code' => Response::HTTP_OK, 'message' => 'No school level for this school name, create this before !');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        return new Response($serializer->serialize($schoolLevel, 'json', $serializationContext->setGroups(['school_all'])), Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Post("/")
     *
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
     */
    public function createOne(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory) {
        $serializationContext = DeserializationContext::create();

        $schoolLevel = $serializer->deserialize($request->getContent(), SchoolLevel::class, 'json', $serializationContext->setGroups(['school_name']));
        $constraintValidator = $validator->validate($schoolLevel);

        if($constraintValidator->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($schoolLevel);
            $em->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'School level are created.');
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse($serializer->serialize($constraintValidator, 'json'), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @Rest\Put("/{name}")
     *
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
     */
    public function updateOne($name, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        $serializationContext = DeserializationContext::create();

        $schoolLevel = $this->getDoctrine()->getRepository(SchoolLevel::class)->findOneBy(['label' => $name]);

        if ($schoolLevel == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'School level are not found, wrong name or already delete.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $updatedSchoolLevel = $serializer->deserialize($request->getContent(), SchoolLevel::class, 'json', $serializationContext->setGroups(['school_name']));
        $constraintValidator = $validator->validate($updatedSchoolLevel);

        if ($constraintValidator->count() == 0) {
            $schoolLevel->update($updatedSchoolLevel);
            $this->getDoctrine()->getManager()->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'School level are updated.');
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse($serializer->serialize($constraintValidator, 'json'), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @Rest\Delete("/{name}")
     *
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
     */
    public function deleteOne($name) {
        $schoolToRemove = $this->getDoctrine()->getRepository(SchoolLevel::class)->findOneBy(['label' => $name]);

        if ($schoolToRemove == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'School level are not found, wrong name or already delete.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $this->getDoctrine()->getManager()->remove($schoolToRemove);
        $this->getDoctrine()->getManager()->flush();

        $data = array('code' => Response::HTTP_OK, 'message' => 'School level are removed.');
        return new JsonResponse($data, Response::HTTP_OK);
    }



}