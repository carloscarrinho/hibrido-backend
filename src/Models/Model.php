<?php

namespace Source\Models;

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

    public function read(string $columns = "*", string $params = null, $terms = null)
    {
        if($params){
            if (is_string($terms)) {
                $this->query = "SELECT {$columns} FROM " . self::$entity . " WHERE {$params} = '{$terms}'";
                parse_str($params, $this->params);
                return $this->query;
            }

            $this->query = "SELECT {$columns} FROM " . self::$entity . " WHERE {$params} = {$terms}";
            parse_str($params, $this->params);
            return $this->query;
        }

        $this->query = "SELECT $columns FROM " . self::$entity;
        return $this->query;
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

    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }
}