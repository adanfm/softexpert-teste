<?php

namespace SoftExpert\Provider;

use Aura\Router\RouterContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class RouterProvider
 *
 * @package SoftExpert\Provider
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
class RouterProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $routerContainer = new RouterContainer();
        /* Registrar as rotas da aplicação */
        $map = $routerContainer->getMap();
        /* Tem a função de identificar a rota que está sendo acessada */
        $matcher = $routerContainer->getMatcher();
        /* Tem a funão de gerar links com base nas rotas registradas*/
        $generator = $routerContainer->getGenerator();

        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        $container['routing'] = $map;
        $container['routing.matcher'] = $matcher;
        $container['routing.generator'] = $generator;
        $container['request'] =  $request;

        $container['route'] = function (Container $container) {
            $matcher = $container['routing.matcher'];
            $request = $container['request'];

            return $matcher->match($request);
        };
    }
}
