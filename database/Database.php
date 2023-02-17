<?php

namespace Database;

use Config\Database as DBConfig;
use mysqli;

/**
 * database
 */
class Database extends DBConfig
{
    private mysqli $connection;
    private string $table;
    private string $query = '';
    private string $type;
    private array $bindValues = [];
    private $stmt;

    /**
     * establish database connection
     * @param $table
     */
    function __construct($table)
    {
        $this->connection = new mysqli(self::$DB_SERVER, self::$DB_USERNAME, self::$DB_PASSWORD, self::$DB_NAME);
        $this->connection->set_charset('utf8mb4');
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->table = $table;
    }

    /**
     * insert records to database
     * @param array $records
     * @return $this
     */
    public function insert(array $records): Database
    {
        $this->type = 'insert';
        $columns =  implode(',', array_keys($records));
        $values =  implode(',', array_fill(0, count($records), '?'));
        foreach ($records as $value) {
            $this->bindValues[] = [$this->getDataType($value), $value];
        }
        $this->query = "INSERT INTO $this->table ($columns) VALUES ($values)";
        return $this;
    }

    /**
     * update database records
     * @param array $records
     * @return $this
     */
    public function update(array $records): Database
    {
        $this->type = 'update';
        $sqlStatement = '';
        $count = 0;
        foreach ($records as $column => $value) {
            $separator = count($records) === ++$count ? '' : ',';
            $this->bindValues[] = [$this->getDataType($value), $value];
            $sqlStatement .= " $column = ?$separator";
        }
        $this->query .= "UPDATE $this->table SET $sqlStatement";
        return $this;
    }

    /**
     * delete database records
     * @return $this
     */
    public function delete(): Database
    {
        $this->type = 'delete';
        $this->query .= "DELETE FROM $this->table";
        return $this;
    }

    /**
     * select table columns
     * @param array $columns
     * @return $this
     */
    public function select(array $columns): Database
    {
        $this->type = 'select';
        $columns = implode(', ', $columns);
        $this->query .= "SELECT $columns FROM $this->table";
        return $this;
    }

    /**
     * add where clause
     * @param array $conditions
     * @return $this
     */
    public function where(array $conditions): Database
    {
        if (!in_array($this->type, ['select', 'update', 'delete'])) {
            die("WHERE can only be added to SELECT, UPDATE OR DELETE");
        }
        $sqlConditions = '';
        foreach ($conditions as [$column, $operator, $value]) {
            $this->bindValues[] = [$this->getDataType($value), $value];
            $sqlConditions .= "$column $operator ?";
        }
        $this->query .= " WHERE $sqlConditions";
        return $this;
    }

    /**
     * add where in clause
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereIn(string $column, array $values): Database
    {
        if (!in_array($this->type, ['select', 'update', 'delete'])) {
            die("WHERE can only be added to SELECT, UPDATE OR DELETE");
        }
        foreach ($values as $value) {
            $this->bindValues[] = [$this->getDataType($value), $value];
        }
        $values =  implode(',', array_fill(0, count($values), '?'));
        $this->query .= " WHERE $column IN($values)";
        return $this;
    }

    /**
     * execute sql statement
     * @return void
     */
    public function execute(): void
    {
        $this->stmt = $this->connection->prepare($this->query);
        if ($this->connection->error) trigger_error($this->connection->error, E_USER_ERROR);
        $this->addBinding();
        $this->stmt->execute();
    }

    /**
     * execute sql statement and return results
     * @return array
     */
    public function get(): array
    {
        if ($this->type != 'select') {
            die("GET can only be added to SELECT");
        }
        $this->execute();
        return $this->stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * get data types
     * @param $data
     * @return string
     */
    public function getDataType($data): string
    {
        $type = 's';
        if (gettype($data) === 'double') $type = 'd';
        elseif (gettype($data) === 'integer') $type = 'i';
        return $type;
    }

    /**
     * add values and data types for bindings
     * @return void
     */
    public function addBinding(): void
    {
        $dataTypes = '';
        $values = [];
        if(count($this->bindValues) === 0) return;
        foreach ($this->bindValues as [$dataType, $value]) {
            $dataTypes .= "$dataType";
            $values[] = $value;
        }
        $this->stmt->bind_param($dataTypes, ...$values);
    }

    /**
     * close database connection
     */
    public function __destruct()
    {
        $this->connection->close();
    }
}

