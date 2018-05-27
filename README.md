LoginGateBundle
==============

[![Build Status](https://travis-ci.org/anyx/LoginGateBundle.svg?branch=master)](https://travis-ci.org/anyx/LoginGateBundle)

This bundle detects brute-force attacks on Symfony applications. It then will disable login for attackers for a certain period of time.
This bundle also provides special events to execute custom handlers when a brute-force attack is detected.

## Compatability
The bundle is since version 0.6 compatible with Symfony 4.

## Installation
Add this bundle via Composer:
```
composer require anyx/login-gate-bundle
```
## Configuration:

Add in app/config/config.yml:

```yml
login_gate:
    storages: ['orm'] # Attempts storages. Available storages: ['orm', 'session', 'mongodb']
    options:
        max_count_attempts: 3
        timeout: 600 #Ban period
        watch_period: 3600 #Only for databases storage. Period of actuality attempts
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
In the following example we import the checker via dependency injection in SecurityController.php.
```php
namespace App\Controller;

use Anyx\LoginGateBundle\Service\BruteForceChecker;

/**
 * @var BruteForceChecker $bruteForceChecker
 */
private $bruteForceChecker;

/**
 * SecurityController constructor.
 * @param BruteForceChecker $bruteForceChecker
 */
public function __construct(BruteForceChecker $bruteForceChecker)
{
    $this->bruteForceChecker = $bruteForceChecker;
}
```
We can now use the checker to see if a person is allowed to login.
```php
$this->bruteForceChecker->canLogin($request)
```
We can also clear the loginattempts when a login is succesful.
```php
$this->bruteForceChecker->getStorage()->clearCountAttempts($request);
```

For more examples take a look at the [tests](https://github.com/anyx/LoginGateBundle/tree/master/Tests).
