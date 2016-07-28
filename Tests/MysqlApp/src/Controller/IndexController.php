<?php

namespace MysqlAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'index.html.php',
            [
                'user' => $this->getUser() ?: 'guest'
            ]
        );
    }
}
