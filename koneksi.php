<?php
class Database {
    private $host   = "localhost";
    private $user   = "root";
    private $pass   = "";
    private $db     = "sma_tunhar";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    // fungsi untuk mengambil koneksi agar bisa dipakai Model
    public function getConnection() {
        return $this->conn;
    }
}
?>
