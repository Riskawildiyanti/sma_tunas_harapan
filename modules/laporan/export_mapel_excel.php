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
header("Content-Disposition: attachment; filename=laporan_mapel.xls");
header("Pragma: no-cache");
header("Expires: 0");

/* =======================
   TABEL EXCEL
======================= */
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>ID Mapel</th>
        <th>Kode Mapel</th>
        <th>Nama Mata Pelajaran</th>
        <th>Kategori</th>
        <th>Tingkat</th>
        <th>Jurusan</th>
        <th>Guru Pengampu</th>
        <th>Jam Pelajaran</th>
        <th>Deskripsi</th>
      </tr>";

/* =======================
   QUERY DATA MAPEL
======================= */
$sql = "SELECT
            m.id_mapel,
            m.kode_mapel,
            m.nama_mapel,
            m.kategori,
            m.tingkat,
            m.jurusan,
            m.jam_pelajaran,
            m.deskripsi,
            g.nama_guru
        FROM mapel m
        LEFT JOIN guru g ON m.id_guru = g.id_guru
        ORDER BY m.nama_mapel ASC";

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
            <td>{$row['id_mapel']}</td>
            <td>{$row['kode_mapel']}</td>
            <td>{$row['nama_mapel']}</td>
            <td>".($row['kategori'] ?? '-')."</td>
            <td>".($row['tingkat'] ?? '-')."</td>
            <td>".($row['jurusan'] ?? '-')."</td>
            <td>".($row['nama_guru'] ?? '-')."</td>
            <td>".($row['jam_pelajaran'] ?? '-')."</td>
            <td>".($row['deskripsi'] ?? '-')."</td>
          </tr>";
    $no++;
}

echo "</table>";
