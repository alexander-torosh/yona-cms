#Yona CMS

[![Build Status](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/badges/build.png?b=master)](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/oleksandr-torosh/yona-cms/?branch=master)

Yona CMS - open source content management system (CMS). Written in Phalcon PHP Framework (version 1.3.x or 2.x supported)  

Has a convenient modular structure. Has simple configuration and architecture. Can be easily modified for any task with any loads.

[Project website](http://yonacms.com/)  
[Documentation](http://doc.yonacms.com/en/)  
[Demo](http://demo.yonacms.com/)  

## Installation

### Via Composer Create-Project

Run this in your terminal to get the latest Composer version:

```bash
curl -sS https://getcomposer.org/installer | php
```

Or if you don't have curl:

```bash
php -r "readfile('https://getcomposer.org/installer');" | php
```

This installer script will simply check some php.ini settings, warn you if they are set incorrectly, and then download the latest composer.phar in the current directory

Then run

```bash
php composer.phar create-project oleksandr-torosh/yona-cms -s dev
```

If you have already installed composer

```bash
composer create-project oleksandr-torosh/yona-cms -s dev
```

### Via Composer json file

Create a composer.json file as follows:
```json
{  
    "require": {  
        "oleksandr-torosh/yona-cms": "dev-master"  
    }  
}
```

Run the composer installer:

```bash
php composer.phar install
```

or

```bash
composer install
```

After updating code, run composer update:
```
composer update
```

### Permissions

```
chmod a+w data -R
chmod a+w web/assets -R
chmod a+w web/img -R
```

[Full installation guide](http://doc.yonacms.com/en/reference/install.html)

##Features

* Yona CMS saves a lot of time in starting necessary basic functionality for any project
* The modular structure with a convenient hierarchy that is based on namespaces
* Each module can serve as a separate independent component. Have its own routes, helpers, css, js assets
* Multi-lingual. Manage an unlimited number of languages and translations directly from admin
* Yona CMS is really fast!

Current version and updates in [CHANGELOG.md](https://github.com/oleksandr-torosh/yona-cms/blob/master/CHANGELOG.md)

##Requirements

* php 5.4+
* phalcon 1.3.4+
* phalcon 2.0.7+
* mysql
* php-intl
* apache (+mod_rewrite) or nginx
