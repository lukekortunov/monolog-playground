# GOAL:

Play with monolog/monolog in details.
Create custom configurations, test different modes, setup different logging backends like sentry, newrelic, elk
Analyze logging performance affects.
Analyze http traffic for elk/sentry/newrelic

[x] Base application
    [x] setup libs: router, di container

[ ] Logging into files
    [ ] Create test that will log into file until file is less than preconfigured value 

[ ] Logging into NewRelic
    [x] Install NewRelic monolog enricher
    [x] Push NewRelic handler to Logger instance
    [x] Start collecting logs in NewRelic
    [ ] Check how BufferHandler works (should be 1 HTTP request to NewRelic with batch of logs)
    [ ] Check how logs are formatted. Probably need custom formatter.

[ ] Logging into Sentry

[ ] Logging into ELK

# Requirements
- PHP ^8.1
- PHP Composer

# Installation
- `git clone https://github.com/lukekortunov/monolog-playground monolog-playground`
- `cd monolog-playground && composer install`
- `make serve` or `php -S localhost:8000 public/index.php`
