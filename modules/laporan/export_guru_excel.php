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
header("Content-Disposition: attachment; filename=laporan_guru.xls");
header("Pragma: no-cache");
header("Expires: 0");

/* =======================
   TABEL EXCEL
======================= */
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>ID Guru</th>
        <th>Nama Guru</th>
        <th>NPWP</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>Guru Pengampu</th>
        <th>Jabatan</th>
        <th>No HP</th>
      </tr>";

/* =======================
   QUERY DATA GURU
======================= */
$sql = "SELECT
            id_guru,
            nama_guru,
            npwp,
            tanggal_lahir,
            alamat,
            guru_pengampu,
            jabatan,
            no_hp
        FROM guru
        ORDER BY nama_guru ASC";

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
            <td>{$row['id_guru']}</td>
            <td>{$row['nama_guru']}</td>
            <td>".($row['npwp'] ?? '-')."</td>
            <td>".($row['tanggal_lahir'] ?? '-')."</td>
            <td>".($row['alamat'] ?? '-')."</td>
            <td>".($row['guru_pengampu'] ?? '-')."</td>
            <td>".($row['jabatan'] ?? '-')."</td>
            <td>".($row['no_hp'] ?? '-')."</td>
          </tr>";
    $no++;
}

echo "</table>";
