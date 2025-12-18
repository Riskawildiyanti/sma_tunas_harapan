<?php
require_once 'koneksi.php';

class NilaiModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $sql = "SELECT n.*, s.nama_siswa, s.nis, m.nama_mapel, k.nama_kelas 
                FROM nilai n 
                JOIN siswa s ON n.id_siswa = s.id_siswa 
                JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                ORDER BY n.tahun_ajaran DESC, n.semester, s.nama_siswa";
        return $this->db->query($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT n.*, s.nama_siswa, s.nis, m.nama_mapel, m.kode_mapel 
                FROM nilai n 
                JOIN siswa s ON n.id_siswa = s.id_siswa 
                JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
                WHERE n.id_nilai = '$id'";
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
    
    public function create($data) {
        $conn = $this->db->getConnection();
        
        $sql = "INSERT INTO nilai (id_siswa, id_mapel, semester, tahun_ajaran, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, grade, keterangan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissdddsss", $data['id_siswa'], $data['id_mapel'], $data['semester'], 
                         $data['tahun_ajaran'], $data['nilai_tugas'], $data['nilai_uts'], 
                         $data['nilai_uas'], $data['nilai_akhir'], $data['grade'], $data['keterangan']);
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        
        return false;
    }
    
    public function update($id, $data) {
        $conn = $this->db->getConnection();
        
        $sql = "UPDATE nilai SET 
                id_siswa = ?, id_mapel = ?, semester = ?, tahun_ajaran = ?, 
                nilai_tugas = ?, nilai_uts = ?, nilai_uas = ?, 
                nilai_akhir = ?, grade = ?, keterangan = ? 
                WHERE id_nilai = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissdddsssi", $data['id_siswa'], $data['id_mapel'], $data['semester'], 
                         $data['tahun_ajaran'], $data['nilai_tugas'], $data['nilai_uts'], 
                         $data['nilai_uas'], $data['nilai_akhir'], $data['grade'], 
                         $data['keterangan'], $id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM nilai WHERE id_nilai = '$id'";
        return $this->db->query($sql);
    }
    
    public function getBySiswa($id_siswa) {
        $sql = "SELECT n.*, m.nama_mapel, m.kode_mapel 
                FROM nilai n 
                JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
                WHERE n.id_siswa = '$id_siswa' 
                ORDER BY n.tahun_ajaran DESC, n.semester, m.nama_mapel";
        return $this->db->query($sql);
    }
    
    public function getByMapel($id_mapel) {
        $sql = "SELECT n.*, s.nama_siswa, s.nis, k.nama_kelas 
                FROM nilai n 
                JOIN siswa s ON n.id_siswa = s.id_siswa 
                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                WHERE n.id_mapel = '$id_mapel' 
                ORDER BY s.nama_siswa";
        return $this->db->query($sql);
    }
    
    public function getRataRataKelas($id_kelas, $id_mapel, $semester, $tahun_ajaran) {
        $sql = "SELECT AVG(n.nilai_akhir) as rata_rata 
                FROM nilai n 
                JOIN siswa s ON n.id_siswa = s.id_siswa 
                WHERE s.id_kelas = '$id_kelas' 
                AND n.id_mapel = '$id_mapel' 
                AND n.semester = '$semester' 
                AND n.tahun_ajaran = '$tahun_ajaran'";
        
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['rata_rata'] ?? 0;
    }
    
    public function search($keyword) {
        $keyword = $this->db->escape($keyword);
        $sql = "SELECT n.*, s.nama_siswa, s.nis, m.nama_mapel, k.nama_kelas 
                FROM nilai n 
                JOIN siswa s ON n.id_siswa = s.id_siswa 
                JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                WHERE s.nama_siswa LIKE '%$keyword%' 
                OR s.nis LIKE '%$keyword%' 
                OR m.nama_mapel LIKE '%$keyword%' 
                OR n.tahun_ajaran LIKE '%$keyword%' 
                ORDER BY n.tahun_ajaran DESC, n.semester, s.nama_siswa";
        return $this->db->query($sql);
    }
}
?>