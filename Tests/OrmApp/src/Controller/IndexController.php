<?php

namespace OrmApp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index()
    {
        $user = $this->getUser();

        return new JsonResponse([
            'message' => 'Index page',
            'user' => $user ? $user->getUsername() : 'guest',
        ]);
    }

    /**
     * @Route("/web", name="web_home", methods={"GET"})
     */
    public function webIndex()
    {
        $user = $this->getUser();

        return new JsonResponse([
            'message' => 'Web Index page',
            'user' => $user ? $user->getUsername() : 'guest',
        ]);
    }
}
