# CHANGELOG

## v0.5.0

- Minimal Phalcon version requirement is 3.0.0;

- Improved count of MySQL queries to fetch translation values for Page and Publication models. Added fields sub-queries for more better performance;

- Created CacheManager component for more effective working with cache keys and results;

## v0.4.2

- Fixed underscore getters/setters on Page and Publication modules;

## v0.4.1

- Fixed error message about image format on Publication Save;

- composer.json Phalcon required version downgraded to 1.3.4;

## v0.4

- Updated Semantic UI to version 2.1.4;

- Fixed bug in \Cms\Scanner;

- Moved all 3rd party libraries to **composer.json**;

- Installation process now requires composer;

- Added Phinx library for database migrations;

- Updated README.md;

## v0.3.1

- Minimized index.php code; Created **YonaCms\Plugin\CheckPoint** plugin for detecting index.php, index.html in Request URI;

- Removed PUBLIC_PATH constant;

- Fix bug with creating new languages;

## v0.3

- Semantic UI updated to v2.0;

- Navigation menu now places in left sidebar;

- Created **\Application\Mvc\Helper\CmsCache** component;

- All caching for translates, languages, publication types now stored in **/app/cache/cms**. Lowered count of SQL queries for cold start;

- Translations for admin replaced to **/app/translations/admin**;

- SEO Manager refactored and removed unused elements; Placed cache to CmsCache;

- TinyMCE 4 replaced by TinyMCE 3; 4th version is not stable and has many bugs..;

## v0.2.1

- Fixed bug with 'SITE NAME' translation in /app/plugins/TitlePlugin.php;

- Fixed bug with Logout button in Admin dashboard in /app/modules/Admin/Controller/IndexController.php::logoutAction();

- Fixed bug with unused fancybox library initialization in Page module;

- Some refactoring for 'contacts.html' page. Now it places in Page module;

- Interface changes for Widget module;

## v0.2  

- You don't need create virtual host for starting using YonaCms anymore. Applied simple localhost dynamic configuration as **http://localhost/yona-cms/web/**;

- Added 'base_path' variable in configuration;

- Configuration file **/app/config/config.php** deprecated and refactored. Now basic config placed in **/app/config/global.php** and **/app/modules/Cms/Config.php**;

- Added some settings in Cms Configuration interface;  

- Custom admin.less placed in **/app/modules/Admin/assets/**

- Deprecated **modules-less** Assets collection;

- Assets list for frontend of site placed in **/app/config/global.php** in 'assets' section;

- Interface and translate fixes;

## v0.1.10  

- Multilingual models cache lifetime placed in **Application\Mvc\Model::CACHE_LIFETIME** constant;

- Multilingual models cache data correctly clean after saving entity;

- Memcache host and port placed in configuration files;

- **Cms\Scanner** for translations scanning only **/app/modules/** and **/app/views/** directories;

- Minor fixes;

- Added LICENSE.md;  

## v0.1.9  

- Semantic UI Bootstrap 3 theme integrated;

- Added support for custom Bootstrap 3 components;  

- Added **Application\Form\Elements\Image** form element and his nice viewing for uploading images using jasny-boostrap library;

- Added bootstrap-datetimepicker form Publication module;

- Some interface fixes;

- Updated DB fileds for publication table;  

## v0.1.8

- Added Tree Category module;

- Changed ACL role/permission system. Added ACL config file - **/app/config/acl.php**. Added roles;

- Added **sitemap.xml** editor;

- Added URL catching type for SEO module. Now you can catch any URL from you site and set to it any meta-tags. Very useful for SEO;

- Update Semantic UI version;

## v0.1.7

- Added a system of modular widgets; Added widget for example at home page;

- Fixed error with caching types of publications;

- Fixed images compression error from Publicaion module;

- Slightly fixed CSS-styles;

- Deleted Slider module;

## v0.1.6

- Improved multilingual caching components;

- Fixed a bug with item "Admin" after the upgrade 0.1.5;

- Added foreign key index from `translate`.`lang` to `language`.`iso`;

## v0.1.5

- Added some translations into Russian to the admin panel;

- Added module Menu; Construction of the menu tree to the definition of active pages;

## v0.1.4

- Identifying and correcting a few PHP Notice;

- Admin interface launched in part to adapt to the English language. The admin can choose the language in the configuration;

## v0.1.3

- Fixed non-critical error validation authorization form to the admin panel;

## v0.1.2

- Added localization administrative panel;

- Added method renderAll () for Application\Form\Form - displays all the form elements;

- Fixed all the bugs in the module SEO. Now properly apply the instructions to modify the title, meta-description, meta-keywords, seo-text. Title replaced completely, without a global suffix;

- SEO manager adds meta tag <meta name = "seo-manager" content = "matched"> in the body of the page, if there is a match for the circumstances and apply the modification;

- Slightly modified multilingual routers. Now to each such routed prefixed 'ml__', it facilitates the work in recognition of the treated routed in dispatchere;

- Fixed a PHP Notice errors;

- Finalization of the interface;

- Updated SQL-dump;

## v0.1.1

- Fixed safety (# 13, # 12, # 11 - thanks for report xboston);

- Added resources for ACL, now all the admin page correctly opened (there was a problem with /cms/language, /seo/manager, /cms/javascript, /cms/javascript);

- Updated version of Semantic UI (1.0.0);

- SQL-dump is laid out as a simple .sql file, not .gz archive;

## v0.1

- Configuration files are divided into directories development, production;

- List of plug-imposed /app/config/modules.php file and automatically converted into the necessary instructions for Phalcon\Loader;

- Improved module Image. Convert images are now engaged Phalcon\Image;

- Remove old unneeded modules;

- Fixed some bugs;

## v. 0.0.13

- Added additional check for the existence of the processing route helper LangSwitcher;

- Fixed a bug with building links in publications;

- So far removed translations admn part. In the future, it will be localized admin and this point will be fully worked out;

- Small changes in the Slider;

## v. 0.0.12

- Added an administrative section to manage languages;

- Make Multi-Module Publication;

- Added the management of the types of publications in the module Publication;

- Added checkbox enable DB-profiler configuration CMS;

## v. 0.0.11

- Added SEO-Manager for installing the title, meta-description, meta-keywords and free text-CEO to any page on the set parameters;

- Minor bug fixes;

## v. 0.0.9

- Added the class Init inside modules. The class serves as the initialization parameters and possible service module;

- Added storage scheme css, less, js files, the individual modules to improve their portability (directory assets within modules);

- Added module Slider;

- In Module Cms added management functionality JS-script on the front end, which are displayed in the head and in the end body. You can use the code to insert Google Analytics, Moscow, etc .;

- Small changes;

## v. 0.0.8

- Added translation management system (instead of Gettext). Management of phrases and their translations can be made directly in the admin;

## v. 0.0.7

- Added module SEO;

- Added the ability to edit the file robots.txt;

- Added a pattern unlimited multi-lingual;

## v. 0.0.6

- Fixed handling and output of 404 and 503 errors;

- Fixed a bug in ajax.js attribute href;

- Rotation.js now switch slides smoothly (smoothness is defined by the config). Little documentation completed yet;

- Installation of the main cache backend in the configuration;

- Installation backend modelsMetadata cache in the configuration;

- Added a constant HOST_HASH. Generated by hostname;

## v. 0.0.5

- To make their own applications for improved initialization error handling and working with AJAX;

- Added tests Codeception;

## v. 0.0.4

- Added module Cms;

- Added functionality for working with the configuration of the application;

## v. 0.0.3

- Fixed a syntax highlighting module Widget;

- Height of code in the module Widget automatically adjusted to the size of the content;

- Few in the admin menu to regroup;

## v. 0.0.2

- Fixed path to elfinder module FileManager;

- Added check for the administrative user 'yona', a warning message about the need to remove it;

- Added a constant ROOT = $ _SERVER ['DOCUMENT_ROOT'];

- Added displaying CHANGELOG.md on the main page of admin panel;

## v. 0.0.1

- Create a repository and made the first commit;
