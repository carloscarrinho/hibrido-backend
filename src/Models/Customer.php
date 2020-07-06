<?php

namespace Source\Models;

use PDO;
use PDOException;
use Source\Services\Connection;

/**
 * Customer Class | Responsável pelas regras de negócio específicas associadas a entidade cliente
 */
class Customer extends Model
{    
    /**
     * Método construtor da Classe Customer, estende as funcionalidades da classe Model
     * Active Record Pattern
     * @return void
     */
    public function __construct()
    {
        parent::__construct('clients');
    }
    
    /**
     * Método de validação dos dados recebidos do cliente
     *
     * @param  array $data
     * @return bool
     */
    private function validateCustomerInfo(array $data): bool
    {
        if (!isset($data['cpf'])) {
            $this->fail = "Por favor, preencha o campo de CPF.";
            return false;
        }

        if (!is_cpf($data['cpf'])) {
            $this->fail = "CPF inválido, por favor tente novamente.";
            return false;
        }

        if(!isset($data['name'])) {
            $this->fail = "Por favor, preencha o campo de nome";
            return false;
        }

        if (!isset($data['email'])) {
            $this->fail = "Por favor, preencha o campo de e-mail";
            return false;
        }

        if (!is_email($data['email'])) {
            $this->fail = "E-mail inválido, por favor tente novamente";
            return false;
        }

        return true;
    }
    
    /**
     * Método de busca de clientes
     *
     * @param  array $data
     * @return array
     */
    public function find(array $data = null): array
    {
        if (isset($data['cpf']) && !is_cpf($data['cpf']) ) {
            $this->message = "CPF inválido, por favor tente novamente.";
            return $this->message;
        }

        $customer = $this->read($data);
        return $customer;
    }
    
    /**
     * Método de cadastro de cliente
     *
     * @param  mixed $data
     * @return string
     */
    public function register(array $data): string
    {
        $isValid = $this->validateCustomerInfo($data);
        if (!$isValid) {
            $this->message = $this->fail;
            return $this->message;
        }

        $customerInfo = [];
        $customerInfo['name'] = "{$data['name']}";
        $customerInfo['email'] = "{$data['email']}";
        if(isset($data['phone'])) {
            $customerInfo['phone'] = "{$data['phone']}";
        }

        $customerCpf = [ "cpf" => $data['cpf']];
        $customer = $customerCpf + $customerInfo;
        $this->query = $this->store($customer);
        return $this->message;
    }
    
    /**
     * Método de atualização dos dados de um cliente
     *
     * @param  array $data
     * @return string
     */
    public function updateCustomer(array $data): string
    {
        $isValid = $this->validateCustomerInfo($data);
        if (!$isValid) {
            $this->message = $this->fail;
            return $this->message;
        }

        $customerInfo = [];
        if(isset($data['name'])) {
            $customerInfo['name'] = "'{$data['name']}'";
        }

        $customerInfo['email'] = "'{$data['email']}'";

        if(isset($data['phone'])) {
            $customerInfo['phone'] = "'{$data['phone']}'";
        }

        $customerCpf = [ "cpf" => "'{$data['cpf']}'"];

        $this->update($customerCpf, $customerInfo);
        return $this->message;
    }
    
    /**
     * Método de remoção de um cliente
     *
     * @param  mixed $data
     * @return string
     */
    public function remove(array $data): string
    {
        if (!isset($data['cpf']) || !is_cpf($data['cpf']) ) {
            $this->message = "CPF inválido, por favor tente novamente.";
            return $this->message;
        }

        $this->delete($data);
        return $this->message;
    }

}