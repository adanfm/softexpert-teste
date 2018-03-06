<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SoftExpert\Application;
use Pimple\Container;

// Service Container Pimple
$container = new Container();

$app = new Application($container);

$app->register(new SoftExpert\Provider\SessionProvider());
$app->register(new SoftExpert\Provider\CartProvider());
$app->register(new SoftExpert\Provider\DBProvider());
$app->register(new \SoftExpert\Provider\TwigProvider());
$app->register(new \SoftExpert\Provider\RouterProvider());

//load das rotas
require_once __DIR__ . '/../src/Controllers/Admin/products.php';
require_once __DIR__ . '/../src/Controllers/Admin/categories.php';
require_once __DIR__ . '/../src/Controllers/Site/ajax.php';
require_once __DIR__ . '/../src/Controllers/Site/default.php';

$app->run();
