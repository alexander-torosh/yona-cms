# Yona CMS

## Installation

Clone project with git clone

    git clone git@github.com:alexander-torosh/yona-cms.git
    
Build Docker images

    docker-compose build
    
Start Docker containers in detached mode

    docker-compose up -d
    
Connect to the main Docker container

    docker exec -it yona-cms bash
    
## Production Performance

@TODO  
Use this article ... https://symfony.com/doc/current/performance.html

## Environment configuration

Cache file `env.php`

Optimize composer autoloader

    composer dump-autoload --no-dev --classmap-authoritative