<?php


$app->post('/ajax-products', function () use ($container) {
    $pdo = $container['pdo'];
    $data = [];
    if (isset($_POST['term'])) {
        $term = filter_input(INPUT_POST, 'term', FILTER_SANITIZE_STRING);

        $sql = 'SELECT p.id as id, p.name as name FROM product p WHERE p.name LIKE ?';
        $statement = $pdo->prepare($sql);
        $statement->execute(['%'.$term . '%']);

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}, 'site.ajax.produtos');

$app->post('/add-product', function () use ($container) {
    $pdo = $container['pdo'];
    $cart = $container['cart'];

    $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $qtd = filter_input(
        INPUT_POST,
        'product_qtd',
        FILTER_SANITIZE_NUMBER_FLOAT,
        FILTER_FLAG_ALLOW_FRACTION
    );

    $sql = <<<EOL
    SELECT 
      p.id as product_id,
      p.name as product_name,
      p.price as product_price,
      c.id as category_id,
      c.name as category_name,
      c.tax_percentage as category_tax_percentage
    FROM
      product p 
    INNER JOIN 
      category c 
    ON 
      c.id = p.category_id 
    WHERE 
      p.id=:idProduct
EOL;

    $query  =   $pdo->prepare($sql);
    $query->bindParam(':idProduct', $productId);
    $query->execute();

    $product = $query->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'failed']);
        exit;
    }

    $cart->addItem([
        'product_id' => $product['product_id'],
        'product_name' => $product['product_name'],
        'qtd' => $qtd,
        'price' => $product['product_price'],
        'category_id' => $product['category_id'],
        'category_name' => $product['category_name'],
        'tax_percentage' => $product['category_tax_percentage'],
    ]);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}, 'site.ajax.add_product');


$app->post('/finish-cart', function () use ($container) {
    $cart = $container['cart'];

    $data = [
        'total_products' => 0,
        'total_tax'     => 0,
    ];

    $cartItem = $cart->getAll();

    foreach ($cartItem as $item) {
        $totalItem = ($item['price']*$item['qtd']);
        $data['total_products'] += $totalItem;
        $data['total_tax'] += ($totalItem/100 * $item['tax_percentage']);
    }

    $cart->removeAll();

    header('application/json');
    echo json_encode([
        'total_products' => number_format($data['total_products'], 2),
        'total_tax' => number_format($data['total_tax'], 2)
    ]);
    exit;
}, 'site.finish_cart');

$app->post('/clear-cart', function () use ($container) {
    $cart = $container['cart'];
    $cart->removeAll();

    header('application/json');
    echo json_encode([
        'status' => 'success'
    ]);
    exit;
}, 'site.clear_cart');
