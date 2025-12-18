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
header("Content-Disposition: attachment; filename=laporan_siswa.xls");

/* =======================
   TABEL EXCEL
======================= */
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>ID Siswa</th>
        <th>NIS</th>
        <th>NISN</th>
        <th>Nama Lengkap</th>
        <th>Jenis Kelamin</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Agama</th>
        <th>Alamat</th>
        <th>No Telepon</th>
        <th>Email</th>
        <th>Nama Ayah</th>
        <th>Nama Ibu</th>
        <th>Pekerjaan Ayah</th>
        <th>Pekerjaan Ibu</th>
        <th>Alamat Orang Tua</th>
        <th>No Telepon Ortu</th>
        <th>Kelas</th>
        <th>Status</th>
        <th>Tahun Masuk</th>
      </tr>";

/* =======================
   QUERY DATA SISWA
======================= */
$sql = "SELECT
            s.id_siswa,
            s.nis,
            s.nisn,
            s.nama_lengkap,
            s.jenis_kelamin,
            s.tempat_lahir,
            s.tanggal_lahir,
            s.agama,
            s.alamat,
            s.no_telepon,
            s.email,
            s.nama_ayah,
            s.nama_ibu,
            s.pekerjaan_ayah,
            s.pekerjaan_ibu,
            s.alamat_ortu,
            s.no_telepon_ortu,
            s.status,
            s.tahun_masuk,
            k.nama_kelas
        FROM siswa s
        LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
        ORDER BY s.nama_lengkap ASC";

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
            <td>{$row['id_siswa']}</td>
            <td>{$row['nis']}</td>
            <td>{$row['nisn']}</td>
            <td>{$row['nama_lengkap']}</td>
            <td>{$row['jenis_kelamin']}</td>
            <td>{$row['tempat_lahir']}</td>
            <td>{$row['tanggal_lahir']}</td>
            <td>{$row['agama']}</td>
            <td>{$row['alamat']}</td>
            <td>{$row['no_telepon']}</td>
            <td>{$row['email']}</td>
            <td>{$row['nama_ayah']}</td>
            <td>{$row['nama_ibu']}</td>
            <td>{$row['pekerjaan_ayah']}</td>
            <td>{$row['pekerjaan_ibu']}</td>
            <td>{$row['alamat_ortu']}</td>
            <td>{$row['no_telepon_ortu']}</td>
            <td>".($row['nama_kelas'] ?? '-')."</td>
            <td>{$row['status']}</td>
            <td>{$row['tahun_masuk']}</td>
          </tr>";
    $no++;
}

echo "</table>";
