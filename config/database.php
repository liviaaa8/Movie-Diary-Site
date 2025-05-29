<?php
class Database {
    private $serverName = "localhost";
    private $database = "MovieDiarySite";
    private $username = ""; // Your SQL Server username
    private $password = ""; // Your SQL Server password
    public $conn; //for connection

    public function getConnection() {
        $this->conn = null;

        try {
            $connectionInfo = array(
                "Database" => $this->database,
                "UID" => $this->username,
                "PWD" => $this->password,
                "CharacterSet" => "UTF-8",
                "ReturnDatesAsStrings" => true
            );

            $this->conn = sqlsrv_connect($this->serverName, $connectionInfo);

            if ($this->conn === false) {
                throw new Exception("Connection failed: " . print_r(sqlsrv_errors(), true));
            }
        } catch(Exception $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>