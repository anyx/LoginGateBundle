<?php

namespace OrmApp\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function indexAction()
    {
        return new JsonResponse(['message' => 'Index!']);
    }
}
