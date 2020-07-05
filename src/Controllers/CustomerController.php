<?php

namespace Source\Controllers;

class CustomerController
{
    public function index()
    {
        $user = new User();
        $user->find();
    }
    
    public function store()
    {
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);
        
        $user = new User();
        $user->store($data);

    }
    
    public function update($data)
    {
        $param['cpf'] = intval($data);
        $body = file_get_contents("php://input");
        $json = json_decode($body, true);
        $data = $param + $json;

        $user = new User();
        $user->alter($data);
    }
    
    public function destroy($data)
    {
        $user = new User();
        $user->destroy(["cpf" => $data]);
    }
}