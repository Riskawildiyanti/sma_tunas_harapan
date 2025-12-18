<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';

$database = new Database();
$koneksi = $database->getConnection();

if (!$koneksi) {
    die("Koneksi database gagal");
}

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

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Guru</title>

<style>
body {
    font-family: Arial, sans-serif;
    font-size: 12px;
}
h2, h4 {
    text-align: center;
    margin: 5px 0;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
table, th, td {
    border: 1px solid black;
}
th, td {
    padding: 6px;
    text-align: center;
}
.footer {
    margin-top: 30px;
    width: 100%;
}
.ttd {
    width: 30%;
    float: right;
    text-align: center;
}
</style>

<script>
window.print();
</script>

</head>

<body>

<h2>LAPORAN DATA GURU</h2>
<h4>SMA TUNAS HARAPAN</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Guru</th>
            <th>Nama Guru</th>
            <th>NPWP</th>
            <th>Tanggal Lahir</th>
            <th>Alamat</th>
            <th>Guru Pengampu</th>
            <th>Jabatan</th>
            <th>No HP</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if ($data && $data->num_rows > 0):
        $no = 1;
        while ($row = $data->fetch_assoc()):
    ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['id_guru']); ?></td>
            <td><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['npwp'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['tanggal_lahir'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['alamat'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['guru_pengampu'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['jabatan'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['no_hp'] ?? '-'); ?></td>
        </tr>
    <?php endwhile; else: ?>
        <tr>
            <td colspan="9">Data guru tidak ditemukan</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="footer">
    <div class="ttd">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Kepala Sekolah</strong></p>
    </div>
</div>

</body>
</html>
