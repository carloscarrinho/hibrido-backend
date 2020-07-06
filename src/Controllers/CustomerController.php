<?php

namespace Source\Controllers;

use Source\Models\Customer;

/**
 * CustomerController Class | Responsável por receber as requisições, encaminhar para
 * o model responsável e retornar a resposta para o frontend.
 */
class CustomerController
{    
    /**
     * Método que retorna os todos os registros encontrados no banco.
     *
     * @return void
     */
    public function findAll(): void
    {
        $customer = new Customer();
        $result = $customer->find();

        header("Content-type: application/json");
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
        
    /**
     * Método que recebe o id no cabeçalho da requisição e retorna um json com a resposta
     * arcerca do registro especificado
     * @param  array $data
     * @return void
     */
    public function findOne(string $data): void
    {
        $param['cpf'] = $data; 
        $customer = new Customer();
        $result = $customer->find($param);

        header("Content-type: application/json");
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
        
    /**
     * Método que recebe os dados do corpo da requisição e retorna um json com a resposta
     * acerca do cadastro no banco
     * @return void
     */
    public function register(): void
    {
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);
        
        $customer = new Customer();
        $message = $customer->register($data);

        header("Content-type: application/json");
        echo json_encode($message, JSON_PRETTY_PRINT);
    }
        
    /**
     * Método que recebe os dados da requisição e retorna um json com a resposta
     * acerca da atualização do registro especificado no banco
     * @param  string $data
     * @return void
     */
    public function update(string $data): void
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
        
    /**
     * Método que recebe o id  e retorna um json com a resposta
     * acerca da remoção do registro especificado no banco
     * @param  string $data
     * @return void
     */
    public function remove(string $data): void
    {
        $customer = new Customer();
        $message = $customer->remove(["cpf" => $data]);

        header("Content-type: application/json");
        echo json_encode($message, JSON_PRETTY_PRINT);
    }
}