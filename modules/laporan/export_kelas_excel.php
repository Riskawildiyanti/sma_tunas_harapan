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

/* =======================
   HEADER EXCEL
======================= */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_kelas.xls");

/* =======================
   TABEL EXCEL
======================= */
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>ID Kelas</th>
        <th>Kode Kelas</th>
        <th>Nama Kelas</th>
        <th>Tingkat</th>
        <th>Jurusan</th>
        <th>Wali Kelas</th>
        <th>Kapasitas</th>
        <th>Tahun Ajaran</th>
      </tr>";

/* =======================
   QUERY DATA KELAS
======================= */
$sql = "SELECT
            k.id_kelas,
            k.kode_kelas,
            k.nama_kelas,
            k.tingkat,
            k.jurusan,
            k.kapasitas,
            k.tahun_ajaran,
            g.nama_guru
        FROM kelas k
        LEFT JOIN guru g ON k.id_guru = g.id_guru
        ORDER BY k.tingkat ASC, k.nama_kelas ASC";

$result = $koneksi->query($sql);

if (!$result) {
    die("Query gagal: " . $koneksi->error);
}

/* =======================
   ISI DATA
======================= */
$no = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$no}</td>
            <td>{$row['id_kelas']}</td>
            <td>{$row['kode_kelas']}</td>
            <td>{$row['nama_kelas']}</td>
            <td>{$row['tingkat']}</td>
            <td>{$row['jurusan']}</td>
            <td>".($row['nama_guru'] ?? '-')."</td>
            <td>{$row['kapasitas']}</td>
            <td>{$row['tahun_ajaran']}</td>
          </tr>";
    $no++;
}

echo "</table>";
