<?php

namespace OrmApp\Controller;

use Anyx\LoginGateBundle\Service\BruteForceChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException('Unable to login');
        }

        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/web/login', name: 'web_login', methods: ['GET', 'POST'])]
    public function formLogin(AuthenticationUtils $authenticationUtils, BruteForceChecker $bruteForceChecker, Request $request): Response
    {
        if (!$bruteForceChecker->canLogin($request)) {
            return new Response('Too many login attempts');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
