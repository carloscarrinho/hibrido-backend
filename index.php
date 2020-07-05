<?php

use Bramus\Router\Router;
use Source\Controllers\CustomerController;

require __DIR__ . "/vendor/autoload.php";

$router = new Router();

$router->get("/clientes", function () {
    $customer = new CustomerController();
    return $customer->index();
});

$router->post("/clientes", function () {
    $customer = new CustomerController();
    return $customer->store();
});

$router->put("/clientes/{cpf}", function () {
    $customer = new CustomerController();
    return $customer->update();
});

$router->delete("/clientes/{cpf}", function () {
    $customer = new CustomerController();
    return $customer->delete();
});

$router->run();