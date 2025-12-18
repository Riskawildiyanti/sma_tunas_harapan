<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../../koneksi.php';

$database = new Database();
$koneksi = $database->getConnection();

if (!$koneksi) {
    die("Koneksi database gagal");
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_nilai.xls");

echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>Nama Siswa</th>
        <th>Kelas</th>
        <th>Mata Pelajaran</th>
        <th>Semester</th>
        <th>Tahun Ajaran</th>
        <th>Nilai Harian</th>
        <th>Nilai UTS</th>
        <th>Nilai UAS</th>
        <th>Nilai Akhir</th>
        <th>Keterangan</th>
      </tr>";

$sql = "SELECT 
            s.nama_lengkap,
            k.nama_kelas,
            m.nama_mapel,
            n.semester,
            n.tahun_ajaran,
            n.nilai_harian,
            n.nilai_uts,
            n.nilai_uas,
            n.nilai_akhir,
            n.keterangan
        FROM nilai n
        JOIN siswa s ON n.id_siswa = s.id_siswa
        JOIN kelas k ON n.id_kelas = k.id_kelas
        JOIN mapel m ON n.id_mapel = m.id_mapel
        ORDER BY s.nama_lengkap ASC";

$result = $koneksi->query($sql);

if (!$result) {
    die("Query gagal: " . $koneksi->error);
}

$no = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$no}</td>
            <td>{$row['nama_lengkap']}</td>
            <td>{$row['nama_kelas']}</td>
            <td>{$row['nama_mapel']}</td>
            <td>{$row['semester']}</td>
            <td>{$row['tahun_ajaran']}</td>
            <td>{$row['nilai_harian']}</td>
            <td>{$row['nilai_uts']}</td>
            <td>{$row['nilai_uas']}</td>
            <td>{$row['nilai_akhir']}</td>
            <td>{$row['keterangan']}</td>
          </tr>";
    $no++;
}

echo "</table>";
