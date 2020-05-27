LoginGateBundle
==============

[![Build Status](https://travis-ci.org/anyx/LoginGateBundle.svg?branch=master)](https://travis-ci.org/anyx/LoginGateBundle)
[![Latest Stable Version](https://poser.pugx.org/anyx/login-gate-bundle/v/stable)](https://packagist.org/packages/anyx/login-gate-bundle)
[![Total Downloads](https://poser.pugx.org/anyx/login-gate-bundle/downloads)](https://packagist.org/packages/anyx/login-gate-bundle)
[![License](https://poser.pugx.org/anyx/login-gate-bundle/license)](https://packagist.org/packages/anyx/login-gate-bundle)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/zryaneskazhev)

This bundle detects brute-force attacks on Symfony applications. It then will disable login for attackers for a certain period of time.
This bundle also provides special events to execute custom handlers when a brute-force attack is detected.

## Compatibility
The bundle is since version 1.0 compatible with Symfony 5.

## Installation
Add this bundle via Composer:
```
composer require anyx/login-gate-bundle
```
## Configuration:

Add in config/packages/login_gate.yml:

```yml
# config/packages/login_gate.yaml

login_gate:
    storages: ['orm'] # Attempts storages. Available storages: ['orm', 'session', 'mongodb']
    options:
        max_count_attempts: 3
        timeout: 600 #Ban period
        watch_period: 3600 #Only for databases storage. Period of actuality attempts
 ```

### :warning: Username resolver (optional).

Since Symfony does not provide a common way to retrieve passed username 
from `AuthenticationFailureEvent` for every possible authentication scenario, 
by default this bundle is trying to retrieve username from `_username` parameter in request's form data.

It means, that if you are using different authentication scenario (`json_login`, for example), 
users with same ip addresses will be indistinguishable. To prevent this,
you probably should create own username resolver and register it in `username_resolver` option:

```php
<?php

namespace App\Service;

use Anyx\LoginGateBundle\Service\UsernameResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Username resolver for json login
 */
class UsernameResolver implements UsernameResolverInterface
{
    public function resolve(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);

        return is_array($requestData) && array_key_exists('username', $requestData) ? $requestData['username'] : null;
    }
}
```

```yml
# config/packages/login_gate.yaml
login_gate:
    storages: ['orm'] # Attempts storages. Available storages: ['orm', 'session', 'mongodb']
    options:
        max_count_attempts: 3
        timeout: 600 #Ban period
        watch_period: 3600 #Only for databases storage. Period of actuality attempts
    username_resolver: App\Service\UsernameResolver
 ```



### Register event handler (optional).
```yml
services:
      acme.brute_force_listener:
          class: Acme\BestBundle\Listener\BruteForceAttemptListener
          tags:
              - { name: kernel.event_listener, event: security.brute_force_attempt, method: onBruteForceAttempt }
```

## Usage

For classic login form authentication we can check count login attempts
before showing form:

```php
namespace App\Controller;

use Anyx\LoginGateBundle\Service\BruteForceChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
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

```
Also there is ability to clear login attempts for request (it happens after successful authentication by default):
```php
$this->bruteForceChecker->getStorage()->clearCountAttempts($request);
```

For more examples take a look at the [tests](https://github.com/anyx/LoginGateBundle/tree/master/Tests).
