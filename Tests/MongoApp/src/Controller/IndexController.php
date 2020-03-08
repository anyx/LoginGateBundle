<?php

namespace MongoApp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/web", name="home", methods={"GET"})
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return new JsonResponse([
            'message' => 'Index page',
            'user' => $user ? $user->getUsername() : 'guest',
        ]);
    }
}
