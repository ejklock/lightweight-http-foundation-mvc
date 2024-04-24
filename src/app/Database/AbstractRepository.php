<?php

namespace App\Database;

use App\Config\Config;
use PDO;

abstract class AbstractRepository
{

    protected $connection;
    protected $connectionName;
    private $table;
    private $model;
    private $primaryKey;
    private $fillable;


    abstract protected function model(): string;
    abstract protected function connectionName(): string;
    abstract protected function fillable(): array;
    abstract protected function getTableName(): string;
    abstract protected function getPrimaryKey(): string;

    protected static function getDatabaseConfig($connectionName)
    {
        return Config::getDatabaseConfig($connectionName);
    }


    public function __construct(PDO $pdoConnection = null)
    {
        $this->connection = $pdoConnection;

        $dbConfig = self::getDatabaseConfig($this->connectionName());

        if (!$this->connection) {

            $this->connection = new PDO(
                "{$dbConfig['driver']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}",
                $dbConfig['username'],
                $dbConfig['password']
            );
        }

        $this->table = $this->getTableName();
        $this->primaryKey = $this->getPrimaryKey();
        $this->model = $this->model();
        $this->fillable = $this->fillable();
        $this->connectionName = $this->connectionName();

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function findById(int $id)
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?"
        );

        $statement->bindParam(1, $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchObject($this->model);
    }

    public function findAll()
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM {$this->table}"
        );

        $statement->execute();

        return  $statement->fetchAll(PDO::FETCH_CLASS, $this->model);
    }

    protected function getAllowedFillableColumns(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    public function insert(array $data): object
    {
        $data = $this->getAllowedFillableColumns($data);
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $this->connection->beginTransaction();

        try {
            $statement = $this->connection->prepare(
                "INSERT INTO {$this->getTableName()} ({$columns}) VALUES ({$values})"
            );

            $statement->execute($data);

            $idInserted = $this->connection->lastInsertId($this->getPrimaryKey());

            $this->connection->commit();

            return $this->findById($idInserted);
        } catch (\Exception $e) {

            $this->connection->rollBack();
            throw $e;
        }
    }

    public function delete(int $id)
    {
        $this->connection->beginTransaction();
        try {
            $statement = $this->connection->prepare(
                "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?"
            );
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            $statement->execute();
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
