Yona CMS
========

Based on Phalcon PHP Framework  
Current version in CHANGELOG.md

Installation
============

- Clone or download repo to your www or other installation directory
- Create MySQL database 'yona-cms'
- Import yona-cms.sql.gz to created database
- Open app/config/application.php and edit DB connect settings:
```
'development' => array(
        'database' => array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '111',
            'dbname' => 'yona-cms',
            'charset' => 'utf8',
        ),
        'profiler' => false,
    ),
```
- Make write/read permissions for app/cache/volt, web/assets, web/img directories
- Launch 'yona-cms' on your host
- Open 'http://yona-cms/admin' and auth
- After authorization remove user 'yona' and change password for user 'admin'

Development mode is default.
You can change application mode to Production in web/.htaccess, just comment this line:
SetEnv APPLICATION_ENV "development"

For production mode change setting in app/config/application.php
```
'production' => array(
    // ...
),
```

Admin authorization
-------------------

http://yona-cms/admin  
yona  
yonacmsphalcon  

**After authorization remove user 'yona' and change password for user 'admin'**