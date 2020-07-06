<?php

use Bramus\Router\Router;
use Source\Controllers\CustomerController;

require __DIR__ . "/vendor/autoload.php";

/** ROUTES */
$router = new Router();

$router->get("/clientes", function () {
    $customer = new CustomerController();
    return $customer->findAll();
});

$router->get("/clientes/{cpf}", function ($cpf) {
    $customer = new CustomerController();
    return $customer->findOne($cpf);
});

$router->post("/clientes", function () {
    $customer = new CustomerController();
    return $customer->register();
});

$router->put("/clientes/{cpf}", function ($cpf) {
    $customer = new CustomerController();
    return $customer->update($cpf);
});

$router->delete("/clientes/{cpf}", function ($cpf) {
    $customer = new CustomerController();
    return $customer->remove($cpf);
});

$router->run();