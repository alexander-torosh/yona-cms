<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Core\Annotations\AnnotationsManager;
use Core\Assets\AssetsHelper;
use Core\Cache\ApcuCache;
use Core\Config\EnvironmentLoader;
use Dashboard\Module as DashboardModule;
use Front\Module as FrontModule;
use Phalcon\Debug;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application as PhalconApplication;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Tag;
use Phalcon\Url;
use Web\Exceptions\AccessDeniedException;

class Application
{
    public function run()
    {
        // DI Container
        $container = new FactoryDefault();

        // Initialize app
        $app = new PhalconApplication($container);

        // Set serverCache service
        $container->setShared('serverCache', (new ApcuCache())->init());

        // Env configuration
        $configLoader = new EnvironmentLoader();
        $configLoader->setDI($container);
        $configLoader->load();

        if ('development' === getenv('APP_ENV')) {
            $debug = new Debug();
            $debug->listen();
        }

        // Create EventsManager Manager
        $webEventsManager = new EventsManager($container);
        $eventsManager = $webEventsManager->getEventsManager();

        // Save Events Manager to DI Container
        $container->setShared('eventsManager', $eventsManager);

        // Bind Events Manager to Application
        $app->setEventsManager($container->get('eventsManager'));

        // Router
        $webRouter = new Router($container, $eventsManager);
        $container->setShared('router', $webRouter->getRouter());

        // Register Web Modules
        $this->registerWebApplicationModules($app);

        // Annotations
        $annotationsManager = new AnnotationsManager($container);
        $container->setShared('annotations', $annotationsManager->getAnnotations());

        // ACL
        $aclManager = new AclManager($container, $eventsManager);
        $container->setShared('acl', $aclManager->getAcl());

        // Default View
        $webView = new View();
        $webView->setEventsManager($eventsManager);
        $container->setShared('view', $webView);

        // Url
        $url = new Url();
        $url->setBaseUri('/');
        $container->setShared('url', $url);

        // Assets Build Resolver
        $assetsHelper = new AssetsHelper($container);
        $container->setShared('assetsHelper', $assetsHelper);

        // @TODO Move it to another place
        // Tag
        Tag::setTitleSeparator(' - ');
        Tag::setTitle('Yona CMS');

        try {
            // Handle request
            $response = $app->handle($_SERVER['REQUEST_URI']);
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

    private function registerWebApplicationModules(PhalconApplication $app)
    {
        $app->registerModules([
            'front' => [
                'className' => FrontModule::class,
                'path' => __DIR__.'/../modules/front/src/Module.php',
            ],
            'dashboard' => [
                'className' => DashboardModule::class,
                'path' => __DIR__.'/../modules/dashboard/src/Module.php',
            ],
        ]);
    }

    private function handleAccessDenied(PhalconApplication $app)
    {
        $app->response
            ->redirect($app->url->get(
                ['for' => 'dashboardLogin'],
                ['redirect' => $app->request->getURI()]
            ))
            ->send()
        ;
    }

    private function notFoundError(PhalconApplication $app)
    {
        Tag::prependTitle('Page Not Found');

        $app->view->render('errors', '404-not-found');
        $app->response
            ->setStatusCode(404)
            ->send()
        ;
    }

    private function serviceUnavailableError(PhalconApplication $app, \Exception $e)
    {
        Tag::prependTitle('Service Unavailable');

        $app->view->render('errors', '503-service-unavailable', [
            'message' => $e->getMessage(),
        ]);
        $app->response
            ->setStatusCode(503)
            ->send()
        ;
    }
}
