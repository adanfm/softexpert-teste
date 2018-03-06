<?php

$app->get('/', function () use ($container) {
    $cart = $container['cart'];
    return $container['view.renderer']->render('index.html.twig', [
        'cart' => $cart->getAll()
    ]);
}, 'default.site');
