<?php

namespace SoftExpert;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SoftExpert\Service\Container as ServiceContainer;
use Symfony\Component\Dotenv\Dotenv;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * Class Application
 * @package SoftExpert
 * @author Adan Felipe Medeiros<adan.grg@gmail.com>
 * @version 1.0
 */
class Application implements \ArrayAccess
{
    use ServiceContainer;

    /**
     * @var Container
     */
    private $container;

    /**
     * Application constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $fileEnv = __DIR__.'/../.env';

        if (!file_exists($fileEnv)) {
            throw new \Exception(sprintf('The file %s not exists', $fileEnv));
        }

        $dotenv = new Dotenv();
        $dotenv->load($fileEnv);

        $this->container = $container;
    }

    /**
     * @param $path
     * @param $callback
     * @param $name
     * @return mixed
     */
    public function get($path, $callback, $name)
    {
        return $this->container['routing']->get($name, $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     * @param $name
     * @return mixed
     */
    public function post($path, $callback, $name)
    {
        return $this->container['routing']->post($name, $path, $callback);
    }

    public function before($path, $callback, $name)
    {
        return $this->container['routing']->before($name, $path, $callback);
    }

    /**
     * @param $provider
     *
     * @throws \Exception
     */
    public function register($provider)
    {
        if ($provider instanceof ServiceProviderInterface) {
            $this->container->register($provider);
            return;
        }

        throw new \Exception(sprintf('The class not is instance of %s', ServiceProviderInterface::class));
    }

    /**
     * Start application
     */
    public function run()
    {
        if (!session_id()) {
            session_start();
        }

        $route = $this->container['route'];
        $request = $this->container['request'];

        if (!$route) {
            echo "Page not found!";
            exit;
        }

        foreach ($route->attributes as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $callable = $route->handler;
        $response = $callable($request);

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }

    public function redirect($url, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit();
    }
}
