<?php
require_once 'koneksi.php';

class MapelModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $sql = "SELECT m.*, g.nama_guru as pengampu_nama 
                FROM mata_pelajaran m 
                LEFT JOIN guru g ON m.id_guru_pengampu = g.id_guru 
                ORDER BY m.tingkat, m.nama_mapel";
        return $this->db->query($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT m.*, g.nama_guru as pengampu_nama 
                FROM mata_pelajaran m 
                LEFT JOIN guru g ON m.id_guru_pengampu = g.id_guru 
                WHERE m.id_mapel = '$id'";
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
        
        $sql = "INSERT INTO mata_pelajaran (" . implode(', ', $fields) . ") 
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
        
        $sql = "UPDATE mata_pelajaran SET " . implode(', ', $sets) . " WHERE id_mapel = ?";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $types = str_repeat('s', count($values) - 1) . 'i';
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM mata_pelajaran WHERE id_mapel = '$id'";
        return $this->db->query($sql);
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM mata_pelajaran";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    public function getByTingkat($tingkat) {
        $sql = "SELECT m.*, g.nama_guru as pengampu_nama 
                FROM mata_pelajaran m 
                LEFT JOIN guru g ON m.id_guru_pengampu = g.id_guru 
                WHERE m.tingkat = '$tingkat' 
                ORDER BY m.nama_mapel";
        return $this->db->query($sql);
    }
    
    public function search($keyword) {
        $keyword = $this->db->escape($keyword);
        $sql = "SELECT m.*, g.nama_guru as pengampu_nama 
                FROM mata_pelajaran m 
                LEFT JOIN guru g ON m.id_guru_pengampu = g.id_guru 
                WHERE m.nama_mapel LIKE '%$keyword%' 
                OR m.kode_mapel LIKE '%$keyword%' 
                OR g.nama_guru LIKE '%$keyword%' 
                ORDER BY m.tingkat, m.nama_mapel";
        return $this->db->query($sql);
    }
}
?>