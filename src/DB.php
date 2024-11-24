<?php
namespace App;

use PDO;
use Exception;
use PDOException;
use PDOStatement;
use App\Utils\Util;

class DB {

    private PDO $conn;
    private string $db_host = 'localhost';
    private string $db_user = 'postgres';
    private string $db_pass = 'postgres';
    private string $db_name = 'android';

    public function __construct() {
        $this->connect();
    }

    public function instnace(): PDO {
        if (empty($this->conn)) {
            $this->connect();
        }
        return $this->conn;
    }

    public function begin_trabsaction(): void {
        $this->conn->beginTransaction();
    }

    public function commit(): void {
        $this->conn->commit();
    }

    public function rollback(): void {
        $this->conn->rollback();
    }

    public function connect(): void {
        if (empty($this->conn)) {
            try {
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // rả về dữ liệu dạng mảng với key là tên của column
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // Trả về một Object của stdClass với tên thuộc tính của Object là tên của column
                    //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, // Gán giá trị của từng column cho từng thuộc tính (property/attribute) của một lớp Class
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                ];
                $dsn = "pgsql:host={$this->db_host};dbname={$this->db_name}";
                // Create pdo connection
                $this->conn = new PDO($dsn, $this->db_user, $this->db_pass, $options);
                // SQLite
                //$this->conn = new PDO("sqlite:path/to/file/app.db");

            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
    }

    public function query(string $sql): mixed {
        return $this->conn->query($sql);
    }

    public function select(string $sql, array $bindings = []): array {
        try {
            $stmt = $this->conn->prepare($sql);

            if (empty($bindings)) {
                $stmt->execute();
            } else if (Util::is_assoc_array($bindings)) {
                $stmt->execute($bindings);
            } else {
                // Otherwise, it is unnamed placeholders
                foreach($bindings as $key => $value) {
                    $stmt->bindParam($key + 1, $value);
                }
                $stmt->execute();
            }
            $out = [];
            while ($row = $stmt->fetch()) {
                array_push($out, $row);
            }
            return $out;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode());
        }
    }

    public function prepare(string $sql, array $bindings = [], string $referance_type = ''): mixed {
        try {
            $stmt = $this->conn->prepare($sql);

            if (!empty($referance_type)) {
                $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $referance_type);
            }
            if (empty($bindings)) {
                $stmt->execute();
                return $stmt;
            }
            // If $bindings is an assoc array, it is named placeholders.
            if (Util::is_assoc_array($bindings)) {
                $stmt->execute($bindings);
                if (str_starts_with(strtoupper($sql), 'INSERT')) {
                    return (int) $this->conn->lastInsertId();
                }
                return $stmt->rowCount();
            }
            // Otherwise, it is unnamed placeholders
            foreach($bindings as $key => $value) {
                $stmt->bindParam($key + 1, $value);
            }
            $stmt->execute();
            if (str_starts_with(strtoupper($sql), 'INSERT')) {
                return (int) $this->conn->lastInsertId();
            }
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode());
        }
    }
}