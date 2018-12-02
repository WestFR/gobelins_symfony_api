<?php

namespace App\Security\Authorization;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $data = array('code' => Response::HTTP_FORBIDDEN, 'message' => 'Access denied for this user profile.');
        return new Response($data, Response::HTTP_FORBIDDEN);
    }
}