<?php

namespace Source\Models;

use PDO;
use PDOException;
use Source\Services\Log;
use Source\Services\Connection;

class Model
{
    protected $fail;

    protected $message;

    protected $query;

    protected $params;

    protected $limit;

    protected $offset;

    protected static $entity;

    public function __construct(string $entity)
    {
        self::$entity = $entity;
    }

    public function read(array $terms = null): array
    {
        $pdo = Connection::connect();
        
        if($terms) {
            $termsSet = [];
            foreach ($terms as $bind => $value) {
                $termsSet[] = "{$bind} = '{$value}'";
            }
            $termsSet = implode(", ", $termsSet);

            $this->query = "SELECT * FROM " . self::$entity . " WHERE {$termsSet}";
            $stmt = $pdo->query($this->query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        $this->query = "SELECT * FROM " . self::$entity;
        $stmt = $pdo->query($this->query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function store(array $data): string
    {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $this->query = "INSERT INTO " . self::$entity . " ({$columns}) VALUES ({$values})";

        try {
            $pdo = Connection::connect();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($this->query);
            $stmt->execute($this->filter($data));
            $pdo->commit();
            $this->message = "Registro efetuado com sucesso";
            return $this->message;

        } catch (PDOException $exception) {
            $log = new Log();
            $log->warning($exception->getMessage(), ["logger" => true]);
            if($pdo) {
                $pdo->rollBack();
            }
            $this->message = "Não foi possível efetuar o registro.";
            return $this->message;
        }
    }

    public function update(array $terms, array $data): string
    {
        $termsSet = [];
        foreach ($terms as $bind => $value) {
            $termsSet[] = "{$bind} = {$value}";
        }
        $termsSet = implode(", ", $termsSet);

        $dataSet = [];
        foreach($data as $bind => $value) {
            $dataSet[] = "{$bind} = {$value}"; 
        }
        $dataSet = implode(", ", $dataSet);

        $this->query = "UPDATE " . self::$entity . " SET {$dataSet} WHERE {$termsSet}";

        try {
            $pdo = Connection::connect();
            $stmt = $pdo->query("SELECT * FROM " . self::$entity . " WHERE {$termsSet}");
            $found = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($found) <= 0) {
                $this->message = "Registro inexistente no banco.";
                return $this->message;
            }

            $pdo->beginTransaction();
            $stmt = $pdo->prepare($this->query);
            $stmt->execute();
            $pdo->commit();

            $this->message = "Registro alterado com sucesso.";
            return $this->message;
            
        } catch (PDOException $exception) {
            $log = new Log();
            $log->warning($exception->getMessage(), ["logger" => true]);
            $this->message = "Não foi possível alterar o registro.";
            if($pdo) {
                $pdo->rollBack();
            }
            return $this->message;
        }
    }

    public function delete(array $terms)
    {
        $termsSet = [];
        foreach($terms as $bind => $value) {
            $termsSet[] = "{$bind} = {$value}";
        }
        $termsSet = implode(", ", $termsSet);

        $this->query = "DELETE FROM " . self::$entity . " WHERE {$termsSet}";

        try {
            $pdo = Connection::connect();
            $stmt = $pdo->query("SELECT * FROM " . self::$entity . " WHERE {$termsSet}");
            $found = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($found) <= 0) {
                $this->message = "Registro inexistente no banco.";
                return $this->message;
            }
            
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($this->query);
            $stmt->execute();
            $pdo->commit();
            $this->message = "Registro removido com sucesso.";
            return $this->message;

        } catch (PDOException $exception) {
            $this->message = "Não foi possível remover o registro.";
            $log = new Log();
            $log->warning($exception->getMessage(), ["logger" => true]);
            if($pdo) {
                $pdo->rollBack();
            }
            return $this->message;
        }   
    }

    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }
}