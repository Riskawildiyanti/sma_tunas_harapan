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

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Nilai</title>

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

<h2>LAPORAN DATA NILAI SISWA</h2>
<h4>Tahun Ajaran</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th>Semester</th>
            <th>Nilai Harian</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Nilai Akhir</th>
            <th>Keterangan</th>
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
            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
            <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
            <td><?= htmlspecialchars($row['nama_mapel']); ?></td>
            <td><?= htmlspecialchars($row['semester']); ?></td>
            <td><?= htmlspecialchars($row['nilai_harian']); ?></td>
            <td><?= htmlspecialchars($row['nilai_uts']); ?></td>
            <td><?= htmlspecialchars($row['nilai_uas']); ?></td>
            <td><?= htmlspecialchars($row['nilai_akhir']); ?></td>
            <td><?= htmlspecialchars($row['keterangan']); ?></td>
        </tr>
    <?php endwhile; else: ?>
        <tr>
            <td colspan="10">Data nilai tidak ditemukan</td>
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
