<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';

$database = new Database();
$koneksi = $database->getConnection();

/* =======================
   QUERY DATA MAPEL
======================= */
$sql = "SELECT 
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

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Mata Pelajaran</title>

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
    vertical-align: top;
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

<h2>LAPORAN DATA MATA PELAJARAN</h2>
<h4>SMA TUNAS HARAPAN</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Mapel</th>
            <th>Nama Mata Pelajaran</th>
            <th>Kategori</th>
            <th>Tingkat</th>
            <th>Jurusan</th>
            <th>Guru Pengampu</th>
            <th>Jam Pelajaran</th>
            <th>Deskripsi</th>
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
            <td><?= htmlspecialchars($row['kode_mapel']); ?></td>
            <td><?= htmlspecialchars($row['nama_mapel']); ?></td>
            <td><?= htmlspecialchars($row['kategori'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['tingkat'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['jurusan'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['jam_pelajaran'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['deskripsi'] ?? '-'); ?></td>
        </tr>
    <?php endwhile; else: ?>
        <tr>
            <td colspan="9">Data mata pelajaran tidak ditemukan</td>
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
