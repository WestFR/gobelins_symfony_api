<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserParent;
use App\Entity\UserTeacher;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController extends FOSRestController
{
    /**
     * @var \JMS\Serializer\Serializer
     */
    protected $serializer;

    /**
     * @var Request
     */
    protected $request;

    /**
     * AbstractController constructor.
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     */
    public function __construct(ContainerInterface $container, RequestStack $requestStack)
    {
        $this->serializer = SerializerBuilder::create()->build();
        $this->request = $requestStack->getCurrentRequest();
        $this->container = $container;
    }

    /**
     * @return string|string[]|null
     */
    public function getToken()
    {
        return $this->request->headers->get('X-AUTH-TOKEN');
    }

    /**
     * Format and return a success response.
     *
     * @param null $data
     * @param array $groups
     * @param int $status
     * @param string $message
     * @return JsonResponse
     */
    public function resSuccess($data = null, array $groups = [], int $status = Response::HTTP_OK, string $message = "")
    {
        $content = [
            'code' => $status,
            'message' => $message,
            'data' => $data
        ];
        if (count($groups) > 0) {
            $content = $this->serializer->serialize($content, 'json', SerializationContext::create()->setGroups($groups));
        } else {
            $content = $this->serializer->serialize($content, 'json');
        }
        return new JsonResponse($content, $status, [], true);
    }

    /**
     * Format and return an error response.
     *
     * @param int $status
     * @param $message
     * @return JsonResponse
     */
    public function resError(int $status, $message)
    {
        $content = [
            'code' => $status,
            'message' => $message
        ];
        return new JsonResponse($content, $status);
    }

    /**
     * @return null|User|UserTeacher|UserParent
     */
    public function getMe()
    {
        $token = $this->request->headers->get('x-auth-token');
        return is_null($token) ? null : $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $token]);
    }
}