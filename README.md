#Yona CMS

Yona CMS - open source content management system (CMS). Written in Phalcon PHP Framework (v 1.3.x)  

Has a convenient modular structure. Has simple configuration and architecture. Can be easily modified for any task with any loads.

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

### Installing via GitHub

Just clone the repository in a common location or inside your project:

```
git clone https://github.com/phalcon/incubator.git
```

For a specific Git branch (eg 1.3.5) please use:

```
git clone -b 1.3.5 git@github.com:phalcon/incubator.git
```

##Features

* Yona CMS saves a lot of time in starting necessary basic functionality for any project
* The modular structure with a convenient hierarchy that is based on namespaces
* Each module can serve as a separate independent component. Have its own routes, helpers, css, js assets
* Multi-lingual. Manage an unlimited number of languages and translations directly from admin
* Yona CMS is really fast!

[Project website](http://yonacms.com/)  
[Documentation](http://doc.yonacms.com/)  

Current version and updates in [CHANGELOG.md](https://github.com/oleksandr-torosh/yona-cms/blob/master/CHANGELOG.md)

##Requirements

* php 5.4+
* phalcon 1.3.2+
* mysql
* php-intl
* apache (+mod_rewrite) or nginx