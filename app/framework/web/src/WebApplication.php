<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Core\Annotations\AnnotationsManager;
use Core\Cache\ApcuCache;
use Core\Config\EnvironmentLoader;
use Core\Assets\AssetsHelper;
use Front\Module as FrontModule;
use Dashboard\Module as DashboardModule;
use Phalcon\Debug;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Tag;
use Phalcon\Url;
use Web\Exceptions\AccessDeniedException;

class WebApplication
{
    public function run()
    {
        // DI Container
        $container = new FactoryDefault();

        // Initialize app
        $app = new Application($container);

        // Set serverCache service
        $container->set('serverCache', (new ApcuCache())->init(), true);

        // Env configuration
        $configLoader = new EnvironmentLoader();
        $configLoader->setDI($container);
        $configLoader->load();

        if (getenv('APP_ENV') === 'development') {
            $debug = new Debug();
            $debug->listen();
        }

        // Create EventsManager Manager
        $webEventsManager = new WebEventsManager($container);
        $eventsManager = $webEventsManager->getEventsManager();

        // Save Events Manager to DI Container
        $container->set('eventsManager', $eventsManager, true);

        // Bind Events Manager to Application
        $app->setEventsManager($container->get('eventsManager'));

        // Router
        $webRouter = new WebRouter($container, $eventsManager);
        $container->set('router', $webRouter->getRouter(), true);

        // Register Web Modules
        $this->registerWebApplicationModules($app);

        // Annotations
        $annotationsManager = new AnnotationsManager($container);
        $container->set('annotations', $annotationsManager->getAnnotations(), true);

        // ACL
        $aclManager = new WebAclManager($container, $eventsManager);
        $container->set('acl', $aclManager->getAcl(), true);

        // Default View
        $webView = new WebView();
        $webView->setEventsManager($eventsManager);
        $container->set('view', $webView, true);

        // Url
        $url = new Url();
        $url->setBaseUri('/');
        $container->set('url', $url);

        // Assets Build Resolver
        $assetsHelper = new AssetsHelper($container);
        $container->set('assetsHelper', $assetsHelper, true);

        // @TODO Move it to another place
        // Tag
        Tag::setTitleSeparator(' - ');
        Tag::setTitle('Yona CMS');

        try {
            // Handle request
            $response = $app->handle($_SERVER["REQUEST_URI"]);
            $response->send();

        } catch (AccessDeniedException $e) {
            // Access Denied
            $this->handleAccessDenied($app);

        } catch (Dispatcher\Exception $e) {
            // 404 Not Found
            $this->notFoundError($app);

        } catch (\Exception $e) {
            // 503 Server Error
            $this->serviceUnavailableError($app, $e);
        }
    }

    private function registerWebApplicationModules(Application $app)
    {
        $app->registerModules([
            'front'     => [
                'className' => FrontModule::class,
                'path'      => __DIR__ . '/../modules/front/src/Module.php',
            ],
            'dashboard' => [
                'className' => DashboardModule::class,
                'path'      => __DIR__ . '/../modules/dashboard/src/Module.php',
            ]
        ]);
    }

    private function handleAccessDenied(Application $app)
    {

    }

    /**
     * @param Application $app
     */
    private function notFoundError(Application $app)
    {
        Tag::prependTitle('Page Not Found');

        $app->view->render('errors', '404-not-found');
        $app->response
            ->setStatusCode(404)
            ->send();
    }

    /**
     * @param Application $app
     * @param \Exception $e
     */
    private function serviceUnavailableError(Application $app, \Exception $e)
    {
        Tag::prependTitle('Service Unavailable');

        $app->view->render('errors', '503-service-unavailable', [
            'message' => $e->getMessage(),
        ]);
        $app->response
            ->setStatusCode(503)
            ->send();
    }
}