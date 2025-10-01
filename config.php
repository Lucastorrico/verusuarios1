<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "asistencia";
    private $connection = null;
    
    public function getConnection() {
        if ($this->connection === null) {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->connection->connect_error) {
                die("Error de conexión: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8");
        }
        
        return $this->connection;
    }
    
    public function closeConnection() {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateRequired($fields) {
    foreach ($fields as $field => $value) {
        if (empty(trim($value))) {
            return "El campo " . $field . " es requerido.";
        }
    }
    return null;
}
?>