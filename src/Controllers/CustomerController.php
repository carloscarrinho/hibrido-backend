<?php

namespace Source\Controllers;

use Source\Models\Customer;

class CustomerController
{
    public function findAll()
    {
        $customer = new Customer();
        $result = $customer->find();

        header("Content-type: application/json");
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    
    public function findOne(string $data)
    {
        $param['cpf'] = $data; 
        $customer = new Customer();
        $result = $customer->find($param);

        header("Content-type: application/json");
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    
    public function register()
    {
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);
        
        $customer = new Customer();
        $message = $customer->register($data);

        header("Content-type: application/json");
        echo json_encode($message, JSON_PRETTY_PRINT);
    }
    
    public function update(string $data)
    {
        $param['cpf'] = $data;
        $body = file_get_contents("php://input");
        $json = json_decode($body, true);
        $data = $param + $json;

        $customer = new Customer();
        $message = $customer->updateCustomer($data);

        header("Content-type: application/json");
        echo json_encode($message, JSON_PRETTY_PRINT);
    }
    
    public function remove(string $data)
    {
        $customer = new Customer();
        $message = $customer->remove(["cpf" => $data]);

        header("Content-type: application/json");
        echo json_encode($message, JSON_PRETTY_PRINT);
    }
}