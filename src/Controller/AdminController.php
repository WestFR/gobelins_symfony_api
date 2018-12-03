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

use App\Entity\User;

/**
 * @Route("/api/admin", name="api_admin")
 */
class AdminController extends Controller {

    /**
     *
     * @Rest\Get("/users")
     *
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
    public function getUsers(Request $request, SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();
        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        if ($apiToken == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Missing arguments (see documentation)');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        return new Response($serializer->serialize($user, 'json', $serializationContext->setGroups(['user_create', 'user_admin'])), Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Put("/users/add/{userId}")
     *
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
     */
    public function addUserAdmin($userId) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        if($user == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User are not found.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $user->setAdminRole();
        $user->update($user);
        $this->getDoctrine()->getManager()->flush();

        $data = array('code' => Response::HTTP_OK, 'message' => 'User role are updated to "ROLE_ADMIN".');
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Delete("/users")
     *
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
     */
    public function removeUserAdmin(Request $request) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        $user->setBasicRole();
        $user->update($user);
        $this->getDoctrine()->getManager()->flush();

        $data = array('code' => Response::HTTP_OK, 'message' => 'User role are updated to "ROLE_USER".');
        return new JsonResponse($data, Response::HTTP_OK);
    }


}