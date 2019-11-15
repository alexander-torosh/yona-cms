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
    
## Development

@TODO Complete this section
    
## Production Performance

@TODO Complete this section

## Environment configuration

**Optimize composer autoloader**

    composer dump-autoload --no-dev --classmap-authoritative