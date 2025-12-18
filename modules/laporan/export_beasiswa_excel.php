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
header("Content-Disposition: attachment; filename=laporan_beasiswa.xls");

/* =======================
   TABEL EXCEL
======================= */
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>ID Beasiswa</th>
        <th>Nama Beasiswa</th>
        <th>Nama Penerima</th>
        <th>Penyedia</th>
        <th>Jenis</th>
        <th>Nominal</th>
        <th>Periode</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Selesai</th>
        <th>Kuota</th>
        <th>Persyaratan</th>
        <th>Status</th>
      </tr>";

/* =======================
   QUERY DATA BEASISWA
======================= */
$sql = "SELECT
            id_beasiswa,
            nama_beasiswa,
            nama_penerima,
            penyedia,
            jenis,
            nominal,
            periode,
            tanggal_mulai,
            tanggal_selesai,
            persyaratan,
            kuota,
            status
        FROM beasiswa
        ORDER BY nama_beasiswa ASC";

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
            <td>{$row['id_beasiswa']}</td>
            <td>{$row['nama_beasiswa']}</td>
            <td>{$row['nama_penerima']}</td>
            <td>{$row['penyedia']}</td>
            <td>{$row['jenis']}</td>
            <td>{$row['nominal']}</td>
            <td>{$row['periode']}</td>
            <td>{$row['tanggal_mulai']}</td>
            <td>{$row['tanggal_selesai']}</td>
            <td>{$row['kuota']}</td>
            <td>{$row['persyaratan']}</td>
            <td>{$row['status']}</td>
          </tr>";
    $no++;
}

echo "</table>";
