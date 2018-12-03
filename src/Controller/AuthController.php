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
 * @Route("/api/auth/", name="api_auth")
 */
class AuthController extends Controller {

    /**
     *
     * @Rest\Post("login")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return a token.",
     * ),
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload for login a user.",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *          @SWG\Property(property="mail", type="string"),
     *          @SWG\Property(property="password", type="string"),
     *     )
     * )
     *
     * @SWG\Tag(name="Auth")
     *
     */
    public function postLogin(Request $request, EncoderFactoryInterface $encoderFactory) {
        $userMail = $request->request->get('mail');
        $userPassword = $request->request->get('password');

        if($userMail == null || $userPassword == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Missing arguments (see documentation)');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        // Verify userMail
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['mail' => $userMail]);

        // Verify userPaswword decode
        $passwordIsValid = null;
        if($user != null) {
            $encoder = $encoderFactory->getEncoder($user);
            $passwordIsValid = $encoder->isPasswordValid($user->getPassword(), $userPassword, null);
        }

        // Check user and valid password
        if ($user == null || $passwordIsValid == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User mail or password is invalid.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        } else {

            // Generate token
            $randomToken = $random = sha1(random_bytes(128));
            $user->setApiToken($randomToken);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'User are login.', 'token' => $randomToken);
            return new JsonResponse($data, Response::HTTP_OK);
        }
    }

    /**
     *
     * @Rest\Post("logout")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Logout a user."
     * ),
     *
     * @SWG\Tag(name="Auth")
     *
     */
    public function postLogout(Request $request) {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        if ($apiToken == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Missing arguments (see documentation)');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);

        if($user == null) {
            $data = array('code' => Response::HTTP_UNAUTHORIZED, 'message' => 'User not found, token is wrong or user is already logout.');
            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $user->setApiToken('');

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $data = array('code' => 200, 'message' => 'User are logout.');
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Post("create")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create a user.",
     * ),
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload for create a user.",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *          @SWG\Property(property="firstname", type="string"),
     *          @SWG\Property(property="lastname", type="string"),
     *          @SWG\Property(property="password", type="string"),
     *          @SWG\Property(property="mail", type="string"),
     *          @SWG\Property(property="phone", type="string"),
     *          @SWG\Property(property="type", type="parent or teacher"),
     *          @SWG\Property(property="borned_at", type="datetime"),
     *
     *     )
     * )
     *
     * @SWG\Tag(name="Auth")
     *
     */
    public function createUser(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory) {
        $serializationContext = DeserializationContext::create();

        $user = $serializer->deserialize($request->getContent(), User::class, 'json', $serializationContext->setGroups(['user_create']));
        $constraintValidator = $validator->validate($user);

        if($constraintValidator->count() == 0) {
            $encoder = $encoderFactory->getEncoder($user);
            $hashedPassword = $encoder->encodePassword($user->getPassword(), null);

            $randomToken = $random = sha1(random_bytes(128));

            $user->setBasicRole();
            $user->setPassword($hashedPassword);
            $user->setApiToken($randomToken);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $data = array('code' => Response::HTTP_OK, 'message' => 'User are created.', 'token' => $randomToken);
            return new JsonResponse($data, Response::HTTP_OK);
        } else {
            return new JsonResponse($serializer->serialize($constraintValidator, 'json'), Response::HTTP_BAD_REQUEST);
        }
    }

}