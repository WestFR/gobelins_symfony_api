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
use JMS\Serializer\DeserializationContext;

use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

use App\Entity\User;

/**
 * @Route("/api/user", name="api_user")
 */
class UserController extends Controller {

    /**
     *
     * @Rest\Get("/me")
     *
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
    public function getProfile(Request $request, SerializerInterface $serializer) {
        $serializationContext = SerializationContext::create();

        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        if ($apiToken == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Missing arguments (see documentation)');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        return new Response($serializer->serialize($user, 'json', $serializationContext->setGroups(['user_create'])), Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Put("/me")
     *
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
     */
    public function updateProfile(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        $serializationContext = DeserializationContext::create();

        if ($apiToken == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Missing arguments (see documentation)');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        $updatedUser = $serializer->deserialize($request->getContent(), User::class, 'json', $serializationContext->setGroups(['user_update']));
        $constraintValidator = $validator->validate($updatedUser);

        if($constraintValidator->count() == 0) {

            if($updatedUser->getPassword() != null) {
                $encoder = $encoderFactory->getEncoder($updatedUser);
                $hashedPassword = $encoder->encodePassword($updatedUser->getPassword(), null);
                $updatedUser->setPassword($hashedPassword);
            }

            $updatedUser->setUpdatedAt(new \DateTime());

            $user->update($updatedUser);
            $this->getDoctrine()->getManager()->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'User are updated.');
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse($serializer->serialize($constraintValidator, 'json'), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @Rest\Delete("/me")
     *
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
     *
     */
    public function deleteProfile(Request $request) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        $userToRemove = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        $this->getDoctrine()->getManager()->remove($userToRemove);
        $this->getDoctrine()->getManager()->flush();

        $data = array('code' => Response::HTTP_OK, 'message' => 'User are removed.');
        return new JsonResponse($data, Response::HTTP_OK);
    }





}