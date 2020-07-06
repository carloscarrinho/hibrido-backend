<?php

namespace Source\Models;

use PDO;
use PDOException;
use Source\Services\Log;
use Source\Services\Connection;

/**
 * Model Class | Responsável pela comunicação com o banco de dados 
 */
class Model
{
    protected string $fail;

    protected string $message;

    protected string $query;

    protected static $entity;
    
    /**
     * Método construtor da classe Model
     * Layer Supertype Pattern
     * @param  mixed $entity
     * @return void
     */
    public function __construct(string $entity)
    {
        self::$entity = $entity;
    }
    
    /**
     * Método que abstrai a leitura de dados no banco
     *
     * @param  array $terms
     * @return array
     */
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
    
    /**
     * Método que abstrai a inserção de dados no banco
     *
     * @param  array $data
     * @return string
     */
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
    
    /**
     * Método que abstrai a alteração de dados no banco
     *
     * @param  array $terms
     * @param  array $data
     * @return string
     */
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
    
    /**
     * Método que abstrai a remoção de dados no banco
     *
     * @param  array $terms
     * @return void
     */
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
    
    /**
     * Método que filtra as chaves e valores para apoio aos métodos que implementam as queries
     *
     * @param  array $data
     * @return array
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }
}