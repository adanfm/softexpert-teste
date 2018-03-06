<?php

$app->get('/admin/categories', function () use ($container) {
    $session = $container['session'];
    $pdo     = $container['pdo'];

    $categories = $pdo->query('SELECT * from category', PDO::FETCH_ASSOC);

    return $container['view.renderer']->render('admin/categories/index.html.twig', [
        'categories' => $categories
    ]);
}, 'admin.categories.index');

$app->get('/admin/categories/add', function () use ($container) {
    return $container['view.renderer']->render('admin/categories/add.html.twig', [
        'category' => []
    ]);
}, 'admin.categories.add');

$app->post('/admin/categories/add', function () use ($container, $app) {
    $session    = $container['session'];
    $generator  = $container['routing.generator'];

    if (isset($_POST['name']) && isset($_POST['tax_percentage'])) {
        //
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $taxPercentage = filter_input(
            INPUT_POST,
            'tax_percentage',
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );

        try {
            $pdo = $container['pdo'];

            $statement = $pdo->prepare('INSERT INTO category (name, tax_percentage) VALUES (?, ?)');
            $result = $statement->execute([$name, $taxPercentage]);
            $msg = ['status' => 'success', 'message' => 'Categoria cadastrada com sucesso!'];
        } catch (PDOException $e) {
            $msg = ['status' => 'failed', 'message' => 'Problemas ao salvar a categoria no banco de dados'];
        }
        $session->addFlash('msg', $msg);
    }

    return $app->redirect($generator->generate('admin.categories.index'));
}, 'admin.categories.post_add');


$app->get('/admin/categories/{id}/edit', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
    $pdo     = $container['pdo'];
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

    return $container['view.renderer']->render('admin/categories/edit.html.twig', [
        'id'       => $id,
        'category' => $category
    ]);
}, 'admin.categories.edit');

$app->post('/admin/categories/{id}/edit', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
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

    if (isset($_POST['name']) && isset($_POST['tax_percentage'])) {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $taxPercentage = filter_input(
            INPUT_POST,
            'tax_percentage',
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );

        try {
            $sql = "UPDATE category set name = :name, tax_percentage = :taxPercentage where id = :id";

            $statement = $pdo->prepare($sql);
            $statement->bindParam('name', $name);
            $statement->bindParam('taxPercentage', $taxPercentage);
            $statement->bindParam('id', $id);

            $statement->execute();

            $msg = ['status' => 'success', 'message' => 'Categoria atualizada com sucesso!'];
        } catch (PDOException $e) {
            $msg = ['status' => 'failed', 'message' => 'Problemas ao atualizar a categoria no banco de dados'];
        }
        $session->addFlash('msg', $msg);
    }

    return $app->redirect($generator->generate('admin.categories.index'));
}, 'admin.categories.post_edit');

$app->post('/admin/categories/{id}/delete', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
    $pdo        = $container['pdo'];
    $session    = $container['session'];

    $id = (int) $request->getAttribute('id');
    try {
        $sql = "DELETE FROM category WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam('id', $id);

        $statement->execute();

        $session->addFlash('msg', ['status' => 'success', 'message' => 'Categoria removida com sucesso!']);

        header('Content-Type: application/json');
        echo json_encode(['success'=> true]);
        exit;
    } catch (PDOException $e) {
        $session->addFlash('msg', ['status' => 'failed', 'message' => 'Não foi possivel remover a categoria!']);
        header('Content-Type: application/json');
        echo json_encode(['failed'=> true]);
        exit;
    }
}, 'admin.categories.delete');

$app->get('/admin/categories/{id}/show', function (\Zend\Diactoros\ServerRequest $request) use ($container, $app) {
    $pdo     = $container['pdo'];
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

    return $container['view.renderer']->render('admin/categories/show.html.twig', [
        'id'       => $id,
        'category' => $category
    ]);
}, 'admin.categories.show');
