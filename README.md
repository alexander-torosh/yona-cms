# php7.0 branch

In this branch I will make current development and project improvements.
Entire project will be refactored. Will be implemented independent API and CLI entry-points.
All frontend assets will be rewritten.

# Yona CMS

[![Build Status](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/badges/build.png?b=php7.0)](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/build-status/php7.0)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/badges/quality-score.png?b=php7.0)](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/badges/quality-score.png?b=php7.0)

Yona CMS - open source content management system (CMS). Written in Phalcon PHP Framework (version 3.x supported)

Has a convenient modular structure. Has simple configuration and architecture. Can be easily modified for any task with any loads.

[Project website](http://yonacms.com/)

# Installation for development

Clone application from git repository.

    git clone https://github.com/oleksandr-torosh/yona-cms -b php7.0
    cd yona-cms

## Docker

The easiest way to run project on your localhost is Docker containers. Application is already configured and all what you need is run next lines:

    docker-compose build
    docker-compose up
    
If you haven't installed Docker yet, please, visit downloading page https://www.docker.com/products/overview#/install_the_platform and install latest version. It's absolutely free.
    
### phpMyAdmin

Open http://localhost:3501
Enter next credentials:

    server: mysql
    login: root
    password: 111

Then import `yona-cms.sql` file to mysql database. You can find this SQL dump file in project root directory.

### Composer

Install composer dependencies:

    composer install

Composer is required. It will install required libraries.
If you have error with **autoload.php** file, the reason - missed **composer update** installation step.

[How to install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

### Node.js

Yona CMS uses Node.js for managing assets and frontend libraries/packages.
[Download and install Node.js](https://nodejs.org/en/download/)

### Bower

If we need just public shared JS/CSS library without additional complexity, we're using Bower.
Install if not installed yet:

    sudo npm install -g bower
    
Then install bower dependencies:

    bower install
    
#### Updating bower libraries/packages

    bower update

### NPM

Yona CMS uses modern npm libraries and packing js/css assets via powerful `webpack` tool.

Install npm dependencies:

    npm install
    
Install Webpack (https://webpack.js.org/)
    
    sudo npm install -g webpack

Compile assets

    webpack
    
Development. Run webpack listener for generating assets on files changes

    webpack -d --watch
    
#### Updating npm libraries/packages

    npm update
    
### Checking your installation

Open http://localhost:3500 and check Yona CMS installation

## Virtual hosting installation

@TODO Write description

### Permissions

```
chmod a+w app/data -R
chmod a+w public/assets -R
chmod a+w public/img -R
chmod a+w public/robots.txt
chmod a+w public/sitemap.txt
```

### Nginx

Example of configuration for php-fpm + nginx. Parameter APPLICATION_ENV has value “development”. Don’t forget remove it on production server.

```
server {

    listen   80;
    server_name yona-cms.dev;

    index index.php;
    set $root_path '/var/www/yona-cms/public';
    root $root_path;

    try_files $uri $uri/ @rewrite;

    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }

    location ~ \.php {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        # fastcgi_pass 127.0.0.1:9000;

        fastcgi_index index.php;

        include /etc/nginx/fastcgi_params;

        fastcgi_split_path_info       ^(.+\.php)(/.+)$;
        fastcgi_param PATH_INFO       $fastcgi_path_info;
        fastcgi_param APPLICATION_ENV "development";
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~* ^/(css|img|js|flv|swf|download)/(.+)$ {
        root $root_path;
    }

    location ~ /\.ht {
        deny all;
    }

}
```

### Apache
.htaccess file are ready configured

### Admin dashboard

Open http://yona-cms/admin and auth:

* login: yona
* password: yonacmsphalcon

Change **admin** user password and delete **yona** user.

### Database
Edit **/app/config/environment/development.php** and setup database connection.
Import MySQL dump file **yona-cms.sql**

### Phinx migrations

https://phinx.org/
Library for creation, executing and rollback migrations

Creation migration class in `app/data/migrations`
```
php vendor/bin/phinx create NewMigrationName
```

Status
```
php vendor/bin/phinx -e development status
```

Executing new migrations
```
php vendor/bin/phinx -e development migrate
```

Rollback
```
php vendor/bin/phinx -e development rollback
```

You can set default environment for your localhost user
```
sudo nano ~/.bashrc
```
Add line
```
export PHINX_ENVIRONMENT=development
```

## Features

* Yona CMS saves a lot of time in starting necessary basic functionality for any project
* The modular structure with a convenient hierarchy that is based on namespaces
* Each module can serve as a separate independent component. Have its own routes, helpers, css, js assets
* Multi-lingual. Manage an unlimited number of languages and translations directly from admin
* Yona CMS is really fast!

Current version and updates in [CHANGELOG.md](https://github.com/oleksandr-torosh/yona-cms/blob/master/CHANGELOG.md)

## Requirements

* php 5.6+
* phalcon 3.0.0+
* mysql
* php-intl
* apache (+mod_rewrite) or nginx
