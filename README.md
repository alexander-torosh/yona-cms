# Yona CMS

[![Build Status](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/badges/build.png?b=master)](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/?branch=master)

Yona CMS - open source content management system (CMS). Written in Phalcon PHP Framework (version 1.3.4+ or 2.0.x supported)  

Has a convenient modular structure. Has simple configuration and architecture. Can be easily modified for any task with any loads.

[Project website](http://yonacms.com/)  
[Documentation](http://doc.yonacms.com/en/) - old version. See installation guide in README.md  
[Demo](http://demo.yonacms.com/)  

## Announce

Now YonaCMS team prepare to new 0.5 release with new project structure and development features.
.. Composer, Bower, Phinx migrations, REST API Bootstrap, Queues, Conjon workers, AWS S3 and other cool stuffs =)

## Installation

### Composer

Run
```
composer create-project oleksandr-torosh/yona-cms -s dev
```

Or create composer.json file and install dependencies:
```json
{  
    "require": {  
        "oleksandr-torosh/yona-cms": "dev-master"  
    }  
}
```
```
composer install
```

After some time, do not forget run composer update for update dependencies:
```
composer update
```

Composer is required. It will install required libraries.
If you have error with **autoload.php** file, the reason - missed **composer update** installation step.

[How to install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

### Permissions

```
chmod a+w data -R
chmod a+w public/assets -R
chmod a+w public/img -R
chmod a+w public/robots.txt
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

        fastcgi_index /index.php;

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

Creation migration class in /data/migrations
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

* php 5.4+
* phalcon 1.3.5+
* phalcon 2.0.7+
* mysql
* php-intl
* apache (+mod_rewrite) or nginx
