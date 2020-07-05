<?php

namespace Source\Models;

class Customer extends Model
{
    public function __construct()
    {
        parent::__construct('clients');
    }

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
}