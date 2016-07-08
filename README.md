LoginGateBundle
==============

This bundle detect attempts brute-force attacks on Symfony applications. Bundle disable login for attackers on certain period.
Also bundle provides special event for execute custom handlers under brute-force attack.

Configuration example:

app/config/security.yml:

```yml
security:
    firewalls:
        #your config
        site:
            form_login:
                failure_handler: anyx.login_failure.handler
                success_handler: anyx.login_success.handler
```

app/config/config.yml:

```yml
login_gate:
    storage_type: composite # Attempts storage. Available 'orm' and 'session' storage. 'composite' includes both storages.
    options:
        max_count_attempts: 4 
        timeout: 600 #Ban period
        watch_period: 3600 #Only for orm storage. Period of actuality attempts

#register event handler
services:
      acme.brute_force_listener:
          class: Acme\BestBundle\Listener\BruteForceAttemptListener
          tags:
              - { name: kernel.event_listener, event: security.brute_force_attempt, method: onBruteForceAttempt }
```
