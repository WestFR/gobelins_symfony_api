<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

use App\Entity\User;
use App\Entity\UserParent;
use App\Entity\Children;

/**
 * @Route("/api/parent", name="api_parent")
 */
class UserParentController extends AbstractController {

    /**
     *
     * @Rest\Get("/childrens")
     *
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
     *
     */
    public function getUserChildrens(Request $request, SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();

        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        if($user == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User not found, token is wrong or user is already logout.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $id = $user->getId();
        $userChildren = $this->getDoctrine()->getRepository(UserParent::class)->findOneBy(['id' => $id]);

        if($userChildren == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User (parent) not found, this user is not a parent type.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $userChildren = $userChildren->getChildrens();

        if (count($userChildren) == 0) {
            $data = array('code' => Response::HTTP_OK, 'message' => 'No children for this user, create this before !');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $groups = $serializationContext->setGroups(['parent_list']);
        return new Response($serializer->serialize($userChildren, 'json', $groups), Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Get("/childrens/{childrenId}")
     *
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
     */
    public function getUserChildren($childrenId,  Request $request, SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();

        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        if($user == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User not found, token is wrong or user is already logout.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $id = $user->getId();
        $userChildren = $this->getDoctrine()->getRepository(UserParent::class)->findOneBy(['id' => $id]);

        if($userChildren == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User (parent) not found, this user is not a parent type.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $userChildren = $userChildren->getSpecificChildren($childrenId);
        if($userChildren == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Children not found for this user, this childrenId is not valid.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $groups = $serializationContext->setGroups(['parent_list']);
        return new Response($serializer->serialize($userChildren, 'json', $groups), Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Post("/childrens")
     *
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
     */
    public function createUserChildren(Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        $serializationContext = DeserializationContext::create();

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        if($user == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User not found, token is wrong or user is already logout.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $id = $user->getId();
        $userChildren = $this->getDoctrine()->getRepository(UserParent::class)->findOneBy(['id' => $id]);

        if($userChildren == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User (parent) not found, this user is not a parent type.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $createChildren = $serializer->deserialize($request->getContent(), Children::class, 'json', $serializationContext->setGroups(['children_create']));
        $constraintValidator = $validator->validate($createChildren);

        if($constraintValidator->count() == 0) {

            $createChildren->setParent($userChildren);
            $userChildren = $userChildren->addChildren($createChildren);

            $em = $this->getDoctrine()->getManager();
            $em->persist($createChildren);
            $em->persist($userChildren);
            $em->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'Children are added to current user.');
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse($serializer->serialize($constraintValidator, 'json'), Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     *
     * @Rest\Put("/childrens/{childrenId}")
     *
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
     */
    public function updateUserChildren($childrenId, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        $serializationContext = DeserializationContext::create();

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        if($user == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User not found, token is wrong or user is already logout.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $id = $user->getId();
        $userChildren = $this->getDoctrine()->getRepository(UserParent::class)->findOneBy(['id' => $id]);

        if($userChildren == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User (parent) not found, this user is not a parent type.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $userChildren = $userChildren->getSpecificChildren($childrenId);
        if($userChildren == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Children not found for this user, this childrenId is not valid.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $updateChildren = $serializer->deserialize($request->getContent(), Children::class, 'json', $serializationContext->setGroups(['children_create']));
        $constraintValidator = $validator->validate($updateChildren);

        if($constraintValidator->count() == 0) {
            $childrenToUpdated = $this->getDoctrine()->getRepository(Children::class)->findOneBy(['id' => $childrenId]);

            if($childrenToUpdated == null) {
                $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Children not found for this user, this childrenId is not valid.');
                return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
            }

            $childrenToUpdated->update($updateChildren);
            $this->getDoctrine()->getManager()->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'Children are updated.');
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse($serializer->serialize($constraintValidator, 'json'), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @Rest\Delete("/childrens/{childrenId}")
     *
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
     */
    public function deleteChildren($childrenId,  Request $request) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        if($user == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User not found, token is wrong or user is already logout.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $id = $user->getId();
        $userChildren = $this->getDoctrine()->getRepository(UserParent::class)->findOneBy(['id' => $id]);

        if($userChildren == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User (parent) not found, this user is not a parent type.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $userToRemove = $userChildren->getSpecificChildren($childrenId);

        if($userToRemove == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Children not found for this user, this childrenId is not valid.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $this->getDoctrine()->getManager()->remove($userToRemove);
        $this->getDoctrine()->getManager()->flush();

        $data = array('code' => Response::HTTP_OK, 'message' => 'Children are removed.');
        return new JsonResponse($data, Response::HTTP_OK);
    }

}