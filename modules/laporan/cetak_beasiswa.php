<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';

$database = new Database();
$koneksi = $database->getConnection();

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
            kuota,
            deskripsi,
            persyaratan,
            status
        FROM beasiswa
        ORDER BY nama_beasiswa ASC";

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Beasiswa</title>

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
.text-left {
    text-align: left;
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

<h2>LAPORAN DATA BEASISWA</h2>
<h4>SMA TUNAS HARAPAN</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
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
            <td><?= htmlspecialchars($row['id_beasiswa']); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['nama_beasiswa']); ?></td>
            <td><?= htmlspecialchars($row['nama_penerima']); ?></td>
            <td><?= htmlspecialchars($row['penyedia']); ?></td>
            <td><?= htmlspecialchars($row['jenis']); ?></td>
            <td>Rp <?= number_format($row['nominal'], 0, ',', '.'); ?></td>
            <td><?= htmlspecialchars($row['periode']); ?></td>
            <td><?= htmlspecialchars($row['tanggal_mulai']); ?></td>
            <td><?= htmlspecialchars($row['tanggal_selesai']); ?></td>
            <td><?= htmlspecialchars($row['kuota']); ?></td>
            <td><?= htmlspecialchars($row['persyaratan']); ?></td>
            <td><?= htmlspecialchars($row['status']); ?></td>
        </tr>
    <?php endwhile; else: ?>
        <tr>
            <td colspan="12">Data beasiswa tidak ditemukan</td>
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
