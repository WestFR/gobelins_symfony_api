<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @param null $data
     * @param array $groups
     * @param int $status
     * @return JsonResponse
     */
    public function sendJson($data = null, array $groups = [], int $status = Response::HTTP_OK)
    {
        if (count($groups) > 0) {
            $data = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups($groups));
        } else {
            $data = $this->serializer->serialize($data, 'json');
        }
        return new JsonResponse($data, $status);
    }
}