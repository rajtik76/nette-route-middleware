<?php

declare(strict_types=1);

namespace App;

use App\Exception\RedirectException;
use App\Interfaces\MiddlewareInterface;
use Nette;
use Nette\Application\Application;
use Nette\Application\Request;
use Nette\Application\Responses\RedirectResponse;
use Nette\Bootstrap\Configurator;


class Bootstrap
{
    private Configurator $configurator;
    private string $rootDir;


    public function __construct()
    {
        $this->rootDir = dirname(__DIR__);
        $this->configurator = new Configurator;
        $this->configurator->setTempDirectory($this->rootDir . '/temp');
    }


    public function bootWebApplication(): Nette\DI\Container
    {
        $this->initializeEnvironment();
        $this->setupContainer();

        $container = $this->configurator->createContainer();

        // Add onRequest middleware hook
        $this->attachMiddlewareHook($container);

        return $container;
    }


    public function initializeEnvironment(): void
    {
        //$this->configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
        $this->configurator->enableTracy($this->rootDir . '/log');

        $this->configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();
    }


    private function setupContainer(): void
    {
        $configDir = $this->rootDir . '/config';
        $this->configurator->addConfig($configDir . '/common.neon');
        $this->configurator->addConfig($configDir . '/services.neon');
    }

    /**
     * Attaches a middleware hook to the application, enabling the execution of registered middleware
     * for incoming requests. Middleware handles can manipulate the request or trigger specific behaviors.
     */
    private function attachMiddlewareHook(Nette\DI\Container $container): void
    {
        $application = $container->getByType(Application::class);

        $application->onRequest[] = function (Application $app, Request $request) use ($container): void {
            $middlewareList = $request->getParameter('middleware') ? $request->getParameter('middleware')() : [];

            foreach ($middlewareList as $middlewareClass) {
                if (!class_exists($middlewareClass)) {
                    continue;
                }

                $middleware = $container->getByType($middlewareClass);

                if ($middleware instanceof MiddlewareInterface) {
                    try {
                        $middleware->handle($request);
                    } catch (RedirectException $e) {
                        $response = new RedirectResponse($e->url, $e->httpCode);
                        $response->send(new Nette\Http\Request(new Nette\Http\UrlScript()), new Nette\Http\Response());
                        exit;
                    }
                }
            }
        };
    }
}
