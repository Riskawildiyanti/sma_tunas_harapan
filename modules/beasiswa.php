<?php
require_once 'koneksi.php';

class BeasiswaModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM beasiswa ORDER BY status DESC, nama_beasiswa ASC";
        return $this->db->query($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM beasiswa WHERE id_beasiswa = '$id'";
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
    
    public function create($data) {
        $conn = $this->db->getConnection();
        
        $fields = [];
        $values = [];
        $placeholders = [];
        
        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = $value;
            $placeholders[] = '?';
        }
        
        $sql = "INSERT INTO beasiswa (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        
        return false;
    }
    
    public function update($id, $data) {
        $conn = $this->db->getConnection();
        
        $sets = [];
        $values = [];
        
        foreach ($data as $field => $value) {
            $sets[] = "$field = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        
        $sql = "UPDATE beasiswa SET " . implode(', ', $sets) . " WHERE id_beasiswa = ?";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $types = str_repeat('s', count($values) - 1) . 'i';
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM beasiswa WHERE id_beasiswa = '$id'";
        return $this->db->query($sql);
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM beasiswa WHERE status = 'Aktif'";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    public function getAktif() {
        $sql = "SELECT * FROM beasiswa 
                WHERE status = 'Aktif' 
                AND tanggal_selesai >= CURDATE() 
                ORDER BY nama_beasiswa ASC";
        return $this->db->query($sql);
    }
    
    public function getPenerima($id_beasiswa) {
        $sql = "SELECT p.*, s.nama_siswa, s.nis, k.nama_kelas 
                FROM penerima_beasiswa p 
                JOIN siswa s ON p.id_siswa = s.id_siswa 
                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                WHERE p.id_beasiswa = '$id_beasiswa' 
                ORDER BY p.tanggal_diterima DESC";
        return $this->db->query($sql);
    }
    
    public function addPenerima($data) {
        $conn = $this->db->getConnection();
        
        $sql = "INSERT INTO penerima_beasiswa (id_beasiswa, id_siswa, tanggal_diterima, semester, tahun_ajaran) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $data['id_beasiswa'], $data['id_siswa'], $data['tanggal_diterima'], 
                         $data['semester'], $data['tahun_ajaran']);
        
        return $stmt->execute();
    }
    
    public function search($keyword) {
        $keyword = $this->db->escape($keyword);
        $sql = "SELECT * FROM beasiswa 
                WHERE nama_beasiswa LIKE '%$keyword%' 
                OR penyedia LIKE '%$keyword%' 
                OR kode_beasiswa LIKE '%$keyword%' 
                ORDER BY status DESC, nama_beasiswa ASC";
        return $this->db->query($sql);
    }
}
?>