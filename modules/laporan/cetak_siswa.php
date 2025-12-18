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
            s.id_kelas,
            s.status,
            s.tahun_masuk,
            k.nama_kelas
        FROM siswa s
        LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
        ORDER BY s.nama_lengkap ASC";

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Siswa</title>

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
    padding: 5px;
    text-align: center;
}
td.text-left {
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

<h2>LAPORAN DATA SISWA</h2>
<h4>SMA TUNAS HARAPAN</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>NISN</th>
            <th>Nama Lengkap</th>
            <th>Jenis Kelamin</th>
            <th>Tempat, Tanggal Lahir</th>
            <th>Agama</th>
            <th>Alamat</th>
            <th>No Telepon</th>
            <th>Email</th>
            <th>Nama Ayah</th>
            <th>Pekerjaan Ayah</th>
            <th>Nama Ibu</th>
            <th>Pekerjaan Ibu</th>
            <th>Alamat Ortu</th>
            <th>No Telepon Ortu</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Tahun Masuk</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if ($data && $data->num_rows > 0):
        $no = 1;
        while ($row = $data->fetch_assoc()):
            $tgl_lahir = $row['tanggal_lahir'] ? date('d-m-Y', strtotime($row['tanggal_lahir'])) : '-';
    ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nis']); ?></td>
            <td><?= htmlspecialchars($row['nisn'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['nama_lengkap']); ?></td>
            <td><?= htmlspecialchars($row['jenis_kelamin'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['tempat_lahir']); ?>, <?= $tgl_lahir; ?></td>
            <td><?= htmlspecialchars($row['agama'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['alamat'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['no_telepon'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['email'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['nama_ayah'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['pekerjaan_ayah'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['nama_ibu'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['pekerjaan_ibu'] ?? '-'); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['alamat_ortu'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['no_telepon_ortu'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['status'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($row['tahun_masuk'] ?? '-'); ?></td>
        </tr>
    <?php endwhile; else: ?>
        <tr>
            <td colspan="19">Data siswa tidak ditemukan</td>
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
