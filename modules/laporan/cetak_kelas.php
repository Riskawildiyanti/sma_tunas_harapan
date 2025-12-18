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
   QUERY DATA KELAS
======================= */
$sql = "SELECT 
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

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Kelas</title>

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

<h2>LAPORAN DATA KELAS</h2>
<h4>SMA TUNAS HARAPAN</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Kelas</th>
            <th>Nama Kelas</th>
            <th>Tingkat</th>
            <th>Jurusan</th>
            <th>Wali Kelas</th>
            <th>Kapasitas</th>
            <th>Tahun Ajaran</th>
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
            <td><?= htmlspecialchars($row['kode_kelas']); ?></td>
            <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
            <td><?= htmlspecialchars($row['tingkat'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['jurusan'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['kapasitas'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-'); ?></td>
        </tr>
    <?php endwhile; else: ?>
        <tr>
            <td colspan="8">Data kelas tidak ditemukan</td>
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
