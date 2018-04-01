# Yona CMS

Second life of Yona CMS ..

# Docker Installation

    git clone git@github.com:oleksandr-torosh/yona-cms.git
    cd yona-cms
    
Copy and edit configuration file `.env`
    
    cp .env.example .env
    
Build Docker images and run containers

    docker-compose build
    docker-compose up -d
    
Connect to Docker container

    docker exec -it yona-php bash
    
Install composer

    composer install -v
    
Install node_modules

    yarn install
    
Open website in your browser http://localhost:11301