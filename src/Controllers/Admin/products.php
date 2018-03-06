<?php

$app->get('/admin/products', function () use ($container) {
    $session = $container['session'];
    $pdo     = $container['pdo'];

    $sql = <<<EOL
  SELECT p.id as id, p.name as name, c.name as category FROM product p INNER JOIN category c ON p.category_id = c.id
EOL;
    $products = $pdo->query($sql, PDO::FETCH_ASSOC);

    return $container['view.renderer']->render('admin/products/index.html.twig', [
        'products' => $products
    ]);
}, 'admin.products.index');

$app->get('/admin/products/add', function () use ($container) {
    $pdo     = $container['pdo'];

    $sql = 'SELECT * from category';
    $categories = $pdo->query($sql, PDO::FETCH_ASSOC);

    return $container['view.renderer']->render('admin/products/add.html.twig', [
        'categories' => $categories
    ]);
}, 'admin.products.add');

$app->post('/admin/products/add', function () use ($container, $app) {
    $session    = $container['session'];
    $generator  = $container['routing.generator'];

    if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['category_id'])) {
        //
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $category = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

        try {
            $pdo = $container['pdo'];

            $statement = $pdo->prepare('INSERT INTO product (name, price, category_id) VALUES (?, ?, ?)');
            $result = $statement->execute([$name, $price, $category]);
            $msg = ['status' => 'success', 'message' => 'Produto cadastrado com sucesso!'];
        } catch (PDOException $e) {
            $msg = ['status' => 'failed', 'message' => 'Problemas ao salvar o produto no banco de dados'];
        }
        $session->addFlash('msg', $msg);
    }

    return $app->redirect($generator->generate('admin.products.index'));
}, 'admin.products.post_add');


$app->get('/admin/products/{id}/edit', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
    $pdo     = $container['pdo'];
    $session    = $container['session'];
    $generator  = $container['routing.generator'];

    $id      = (int) $request->getAttribute('id');
    $query  =   $pdo->prepare("SELECT * FROM product c WHERE c.id=:idProduct");
    $query->bindParam(':idProduct', $id);
    $query->execute();

    $product = $query->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $session->addFlash('msg', ['status' => 'failed', 'message' => 'Produto não existe no banco de dados']);
        return $app->redirect($generator->generate('admin.categories.index'));
    }

    $sql = 'SELECT * from category';
    $categories = $pdo->query($sql, PDO::FETCH_ASSOC);

    return $container['view.renderer']->render('admin/products/edit.html.twig', [
        'id'       => $id,
        'product' => $product,
        'categories' => $categories
    ]);
}, 'admin.products.edit');

$app->post('/admin/products/{id}/edit', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
    $pdo        = $container['pdo'];
    $session    = $container['session'];
    $generator  = $container['routing.generator'];

    $id      = (int) $request->getAttribute('id');
    $query  =   $pdo->prepare("SELECT * FROM category c WHERE c.id=:idCategory");
    $query->bindParam(':idCategory', $id);
    $query->execute();

    $category = $query->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        $session->addFlash('msg', ['status' => 'failed', 'message' => 'Categoria não existe no banco de dados']);
        return $app->redirect($generator->generate('admin.categories.index'));
    }

    if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['category_id'])) {
        //
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $category = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

        try {
            $sql = "UPDATE product set name = :name, price = :price, category_id = :categoryId where id = :id";

            $statement = $pdo->prepare($sql);
            $statement->bindParam('name', $name);
            $statement->bindParam('price', $price);
            $statement->bindParam('categoryId', $category);
            $statement->bindParam('id', $id);

            $statement->execute();

            $msg = ['status' => 'success', 'message' => 'Produto atualizado com sucesso!'];
        } catch (PDOException $e) {
            $msg = ['status' => 'failed', 'message' => 'Problemas ao atualizar o produto no banco de dados'];
        }
        $session->addFlash('msg', $msg);
    }

    return $app->redirect($generator->generate('admin.products.index'));
}, 'admin.products.post_edit');

$app->post('/admin/products/{id}/delete', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
    $pdo        = $container['pdo'];
    $session    = $container['session'];

    $id = (int) $request->getAttribute('id');
    try {
        $sql = "DELETE FROM product WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam('id', $id);

        $statement->execute();

        $session->addFlash('msg', ['status' => 'success', 'message' => 'Produto removido com sucesso!']);

        header('Content-Type: application/json');
        echo json_encode(['success'=> true]);
        exit;
    } catch (PDOException $e) {
        $session->addFlash('msg', ['status' => 'failed', 'message' => 'Não foi possivel remover a categoria!']);
        header('Content-Type: application/json');
        echo json_encode(['failed'=> true]);
        exit;
    }
}, 'admin.products.delete');

$app->get('/admin/products/{id}/show', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
    $pdo     = $container['pdo'];
    $session    = $container['session'];
    $generator  = $container['routing.generator'];

    $id      = (int) $request->getAttribute('id');
    $sql = <<<EOL
  SELECT 
    p.id as id, p.name as name, p.price as price, c.name as category 
  FROM 
    product p 
  INNER JOIN 
    category c ON p.category_id = c.id 
  WHERE 
    p.id=:idProduct
EOL;

    $query  =   $pdo->prepare($sql);
    $query->bindParam(':idProduct', $id);
    $query->execute();

    $product = $query->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $session->addFlash('msg', ['status' => 'failed', 'message' => 'Produto não existe no banco de dados']);
        return $app->redirect($generator->generate('admin.products.index'));
    }

    return $container['view.renderer']->render('admin/products/show.html.twig', [
        'id'       => $id,
        'product' => $product
    ]);
}, 'admin.products.show');
