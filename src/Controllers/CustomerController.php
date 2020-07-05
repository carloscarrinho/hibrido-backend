<?php

namespace Source\Controllers;

use Source\Models\Customer;

class CustomerController
{
    public function index()
    {
        $customer = new Customer();
        // $customer->find();
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
    
    public function update($data)
    {
        $param['cpf'] = $data;
        $body = file_get_contents("php://input");
        $json = json_decode($body, true);
        $data = $param + $json;

        $customer = new Customer();
        // $customer->updateUser($data);
    }
    
    public function destroy($data)
    {
        $customer = new Customer();
        // $customer->destroy(["cpf" => $data]);
    }
}