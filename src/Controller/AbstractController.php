<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
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
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->serializer = SerializerBuilder::create()->build();
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Format and return a success response.
     *
     * @param null $data
     * @param array $groups
     * @param int $status
     * @return JsonResponse
     */
    public function resSuccess($data = null, array $groups = [], int $status = Response::HTTP_OK)
    {
        if (count($groups) > 0) {
            $data = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups($groups));
        } else {
            $data = $this->serializer->serialize($data, 'json');
        }
        return new JsonResponse($data, $status);
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
        $data = [
            'code' => $status,
            'message' => $message
        ];
        return new JsonResponse($data, $status);
    }
}