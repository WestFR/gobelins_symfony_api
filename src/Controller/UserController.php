<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

use FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

use App\Entity\User;

/**
 * @Route("/api/user/", name="api_user")
 */
class UserController extends Controller {

    /**
     *
     * @Rest\Get("myAccount")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the authenticated user."
     * )
     *
     * @SWG\Tag(name="User")
     *
     */
    public function getAccount(Request $request, SerializerInterface $serializer) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        if ($apiToken == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Missing arguments (see documentation)');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        return new Response($serializer->serialize($user, 'json'), Response::HTTP_OK);

    }

}