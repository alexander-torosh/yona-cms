<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Core\Annotations\AnnotationsManager;
use Core\___Assets\AssetsHelper;
use Core\Cache\ApcuCache;
use Core\Config\EnvironmentLoader;
use Dashboard\Module as DashboardModule;
use Front\Module as FrontModule;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Debug;
use Phalcon\Di\DiInterface;
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

        $this->initConfiguration($container);

        // Phalcon Debug
        if ('development' === getenv('APP_ENV')) {
            $debug = new Debug();
            $debug->listen();
        }

        $this->initEventsManager($app, $container);
        $this->initDispatchingProcess($app, $container);
        $this->initAnnotations($container);
        $this->initAcl($container);
        $this->initView($container);
        $this->initApplicationServices($container);

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

    private function initConfiguration(DiInterface $container)
    {
        $configLoader = new EnvironmentLoader();
        $configLoader->setDI($container);
        $configLoader->load();
    }

    private function initEventsManager(PhalconApplication $app, DiInterface $container)
    {
        // Create EventsManager Manager
        $webEventsManager = new EventsManager($container);
        $eventsManager = $webEventsManager->getEventsManager();

        // Save Events Manager to DI Container
        $container->setShared('eventsManager', $eventsManager);

        // Bind Events Manager to Application
        $app->setEventsManager($eventsManager);
    }

    private function initDispatchingProcess(PhalconApplication $app, DiInterface $container)
    {
        // Router
        $webRouter = new Router();
        $webRouter->setDI($container);
        $container->setShared('router', $webRouter->initRouter());

        // Register Application Modules
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

    private function initAnnotations(DiInterface $container)
    {
        $annotationsManager = new AnnotationsManager();
        $annotationsManager->setDI($container);
        $container->setShared('annotations', $annotationsManager->initAnnotations());
    }

    /**
     * @param DiInterface $container
     * @throws InvalidArgumentException
     */
    private function initAcl(DiInterface $container)
    {
        $aclManager = new AclManager();
        $aclManager->setDI($container);
        $container->setShared('acl', $aclManager->initAcl());
    }

    private function initView(DiInterface $container)
    {
        $webView = new View();
        $webView->setEventsManager($container->get('eventsManager'));
        $container->setShared('view', $webView);
    }

    private function initApplicationServices(DiInterface $container)
    {
        // URL service. Set base URI
        $container->get('url')->setBaseUri('/');

        // Set Title tag separator
        Tag::setTitleSeparator(' - ');

        // Assets Build Resolver
        $assetsHelper = new AssetsHelper($container);
        $container->setShared('assetsHelper', $assetsHelper);
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
