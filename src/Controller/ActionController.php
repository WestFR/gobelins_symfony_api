<?php

namespace App\Controller;

use App\Entity\ActionCustom;
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

use App\Entity\Action;

/**
 * @Route("/api/", name="api_actions")
 */
class ActionController extends Controller {

    /**
     *
     * @Rest\Get("actions/")
     *
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
     * @SWG\Tag(name="Actions")
     *
     */
    public function getAll(SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();

        $actions = $this->getDoctrine()->getRepository(Action::class)->findAll();

        if ($actions == null) {
            $data = array('code' => Response::HTTP_OK, 'message' => 'No action for this moment, create one before !');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        return new Response($serializer->serialize($actions, 'json', $serializationContext->setGroups(['action_list'])),  Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Get("actions/{actionId}")
     *
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
     * @SWG\Tag(name="Actions")
     *
     */
    public function getOne($actionId, SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();

        $action = $this->getDoctrine()->getRepository(Action::class)->findOneBy(['id' => $actionId]);

        if ($action == null) {
            $data = array('code' => Response::HTTP_OK, 'message' => 'No action for this id, id is wrong or action is not existing !');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        return new Response($serializer->serialize($action, 'json', $serializationContext->setGroups(['action_list'])), Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Post("actions/")
     *
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
     * @SWG\Tag(name="Actions")
     *
     * @deprecated
     */
    public function createOneForTeacher(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory) {
        $data = array('code' => Response::HTTP_OK, 'message' => 'This route is not available for this moment.');
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Put("actions/{actionId}")
     *
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
     * @SWG\Tag(name="Actions")
     *
     * @deprecated
     */
    public function modifyOneForTeacher($action, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory) {
        $data = array('code' => Response::HTTP_OK, 'message' => 'This route is not available for this moment.');
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Post("admin/actions/")
     *
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
     * @SWG\Tag(name="Admin")
     *
     */
    public function createOneForAdmin(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory) {
        $serializationContext = DeserializationContext::create();
        $actionType = $request->get('type');

        if($actionType  == "action") {
            $action = $serializer->deserialize($request->getContent(), Action::class, 'json', $serializationContext->setGroups(['parent_list']));
            $constraintValidator = $validator->validate($action);
        } else {
            $action = $serializer->deserialize($request->getContent(), ActionCustom::class, 'json', $serializationContext->setGroups(['parent_list']));
            $constraintValidator = $validator->validate($action);
        }

        if($constraintValidator->count() == 0) {
            $em = $this->getDoctrine()->getManager();

            //$action->type = "action";
            //$action->setCreator($user)


            $em->persist($action);
            $em->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'Action are created.');
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse($serializer->serialize($constraintValidator, 'json'), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @Rest\Put("admin/actions/{actionId}")
     *
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
     * @SWG\Tag(name="Admin")
     *
     * @deprecated
     */
    public function modifyOneForAdmin($actionId,  Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory) {
        $data = array('code' => Response::HTTP_OK, 'message' => 'This route is not available for this moment.');
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Delete("admin/actions/{actionId}")
     *
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
     * @SWG\Tag(name="Admin")
     *
     */
    public function deleteOne($actionId) {
        $actionToRemove = $this->getDoctrine()->getRepository(Action::class)->findOneBy(['id' => $actionId]);

        if ($actionToRemove == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Action are not found, wrong id or already delete.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $this->getDoctrine()->getManager()->remove($actionToRemove);
        $this->getDoctrine()->getManager()->flush();

        $data = array('code' => Response::HTTP_OK, 'message' => 'Action are removed.');
        return new JsonResponse($data, Response::HTTP_OK);
    }

}