<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Core\Cache\ApcuCache;
use Core\Config\EnvironmentLoader;
use Core\Assets\BuildResolver as AssetsBuildResolver;
use Front\Module as FrontModule;
use Dashboard\Module as DashboardModule;
use Phalcon\Debug;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Tag;
use Phalcon\Url;

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

        $container->set('router', (new WebRouter())->init(), true);

        // Register Web Modules
        $this->registerWebApplicationModules($app);

        // Create and bind an EventsManager Manager to the app
        $container->set('eventsManager', (new WebEventsManager())->init(), true);

        // Default View
        $container->set('view', new WebView(), true);

        // Url
        $url = new Url();
        $url->setBaseUri('/');
        $container->set('url', $url);

        // Assets Build Resolver
        $entrypointsManager = new AssetsBuildResolver($container);
        $container->set('assetsBuildResolver', $entrypointsManager, true);

        // @TODO Move it to another place
        // Tag
        Tag::setTitleSeparator(' - ');
        Tag::setTitle('Yona CMS');

        try {
            // Handle request
            $response = $app->handle($_SERVER["REQUEST_URI"]);
            $response->send();

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