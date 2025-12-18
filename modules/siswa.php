<?php
// ==================== DEBUG ====================
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<div style='background:#f8d7da;padding:10px;margin:10px;border:1px solid #f5c6cb;'>";
echo "<h3>🛠️ DEBUG MODE - Data Siswa</h3>";
echo "<strong>File:</strong> " . __FILE__ . "<br>";
echo "<strong>Waktu:</strong> " . date('H:i:s') . "<br><br>";
require_once '../koneksi.php';

class SiswaModel {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection(); // gunakan ini untuk koneksi
    }
    
    public function getAll() {
        $sql = "SELECT s.*, k.nama_kelas 
                FROM siswa s 
                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                ORDER BY s.nama_siswa ASC";
        return $this->conn->query($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT s.*, k.nama_kelas, k.kode_kelas 
                FROM siswa s 
                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                WHERE s.id_siswa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function create($data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO siswa ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) return false;

        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    public function update($id, $data) {
        $sets = implode(', ', array_map(fn($field) => "$field = ?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;

        $sql = "UPDATE siswa SET $sets WHERE id_siswa = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $types = str_repeat('s', count($data)) . 'i';
        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM siswa WHERE id_siswa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM siswa WHERE status_siswa = 'Aktif'";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }
    
    public function countHariIni() {
        $sql = "SELECT COUNT(*) as total FROM siswa WHERE DATE(created_at) = CURDATE()";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }
    
    public function getByKelas($id_kelas) {
        $sql = "SELECT * FROM siswa 
                WHERE id_kelas = ? AND status_siswa = 'Aktif' 
                ORDER BY nama_siswa ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_kelas);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    public function search($keyword) {
        $keyword = "%$keyword%";
        $sql = "SELECT s.*, k.nama_kelas 
                FROM siswa s 
                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                WHERE s.nama_siswa LIKE ? 
                OR s.nis LIKE ? 
                OR s.nisn LIKE ?
                ORDER BY s.nama_siswa ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
