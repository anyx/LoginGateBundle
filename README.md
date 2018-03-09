LoginGateBundle
==============

[![Build Status](https://travis-ci.org/anyx/LoginGateBundle.svg?branch=master)](https://travis-ci.org/anyx/LoginGateBundle)

This bundle detect attempts brute-force attacks on Symfony applications. Bundle disable login for attackers on certain period.
Also bundle provides special event for execute custom handlers under brute-force attack.

Configuration example:

app/config/config.yml:

```yml
login_gate:
    storages: ['orm'] # Attempts storages. Available storages: ['orm', 'session', 'mongodb']
    options:
        max_count_attempts: 3
        timeout: 600 #Ban period
        watch_period: 3600 #Only for databases storage. Period of actuality attempts

#register event handler (optional).
services:
      acme.brute_force_listener:
          class: Acme\BestBundle\Listener\BruteForceAttemptListener
          tags:
              - { name: kernel.event_listener, event: security.brute_force_attempt, method: onBruteForceAttempt }
```

More examples you can see in [tests](https://github.com/anyx/LoginGateBundle/Tests)
