<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';

$database = new Database();
$koneksi = $database->getConnection();

// Query disesuaikan dengan field baru
$sql = "SELECT 
            nama_siswa,
            mata_pelajaran,
            guru,
            kelas,
            semester,
            tahun_ajaran,
            nilai_harian,
            nilai_uts,
            nilai_uas,
            nilai_akhir,
            keterangan
        FROM nilai 
        ORDER BY nama_siswa ASC";

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
    margin: 20px;
    line-height: 1.4;
}
.header {
    text-align: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #000;
    padding-bottom: 10px;
}
h1 {
    font-size: 20px;
    margin: 5px 0;
    text-transform: uppercase;
}
h3 {
    font-size: 16px;
    margin: 5px 0;
    color: #333;
}
.info {
    text-align: center;
    margin-bottom: 15px;
}
.info p {
    margin: 3px 0;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 11px;
}
table, th, td {
    border: 1px solid #000;
}
th {
    background-color: #f0f0f0;
    font-weight: bold;
    padding: 8px 5px;
    text-align: center;
    vertical-align: middle;
}
td {
    padding: 6px 4px;
    vertical-align: middle;
}
.text-left {
    text-align: left;
}
.text-center {
    text-align: center;
}
.text-right {
    text-align: right;
}
.number {
    font-family: 'Courier New', monospace;
}
.footer {
    margin-top: 30px;
    width: 100%;
}
.ttd-section {
    float: right;
    width: 300px;
    text-align: center;
}
.ttd-space {
    height: 70px;
}
.ttd-line {
    border-top: 1px solid #000;
    width: 200px;
    margin: 0 auto;
    padding-top: 5px;
}
.page-break {
    page-break-before: always;
}
.no-data {
    text-align: center;
    font-style: italic;
    color: #666;
    padding: 20px;
}
.date-info {
    text-align: left;
    margin-top: 15px;
    font-size: 11px;
    color: #666;
}
@media print {
    @page {
        size: A4 landscape;
        margin: 15mm;
    }
    body {
        margin: 0;
        padding: 0;
    }
    .no-print {
        display: none;
    }
}
</style>

<script>
window.onload = function() {
    window.print();
}
</script>

</head>

<body>

<div class="header">
    <h1>LAPORAN DATA NILAI SISWA</h1>
    <h3>SMA TUNAS HARAPAN</h3>
</div>

<div class="info">
    <?php
    // Ambil tahun ajaran jika ada data
    $tahun_ajaran_info = "Tahun Ajaran: -";
    if ($data && $data->num_rows > 0) {
        $first_row = $data->fetch_assoc();
        $tahun_ajaran_info = "Tahun Ajaran: " . htmlspecialchars($first_row['tahun_ajaran']);
        // Reset pointer untuk loop berikutnya
        $data->data_seek(0);
    }
    ?>
    <p><strong><?php echo $tahun_ajaran_info; ?></strong></p>
    <p><strong>Tanggal Cetak: <?php echo date('d F Y, H:i'); ?></strong></p>
</div>

<table>
    <thead>
        <tr>
            <th width="30">No</th>
            <th width="150">Nama Siswa</th>
            <th width="120">Mata Pelajaran</th>
            <th width="100">Guru</th>
            <th width="60">Kelas</th>
            <th width="70">Semester</th>
            <th width="100">Tahun Ajaran</th>
            <th width="70">Nilai Harian</th>
            <th width="70">Nilai UTS</th>
            <th width="70">Nilai UAS</th>
            <th width="70">Nilai Akhir</th>
            <th width="120">Keterangan</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if ($data && $data->num_rows > 0):
        $no = 1;
        while ($row = $data->fetch_assoc()):
            // Format nilai dengan 2 desimal
            $nilai_harian = $row['nilai_harian'] !== null ? number_format((float)$row['nilai_harian'], 2) : '-';
            $nilai_uts = $row['nilai_uts'] !== null ? number_format((float)$row['nilai_uts'], 2) : '-';
            $nilai_uas = $row['nilai_uas'] !== null ? number_format((float)$row['nilai_uas'], 2) : '-';
            $nilai_akhir = $row['nilai_akhir'] !== null ? number_format((float)$row['nilai_akhir'], 2) : '-';
            
            // Tentukan kelas CSS untuk nilai akhir
            $nilai_akhir_class = '';
            if ($row['nilai_akhir'] !== null) {
                $nilai = (float)$row['nilai_akhir'];
                if ($nilai >= 80) {
                    $nilai_akhir_class = 'number';
                } elseif ($nilai >= 60) {
                    $nilai_akhir_class = 'number';
                } else {
                    $nilai_akhir_class = 'number';
                }
            }
    ?>
        <tr>
            <td class="text-center"><?= $no++; ?></td>
            <td class="text-left"><?= htmlspecialchars($row['nama_siswa']); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['mata_pelajaran']); ?></td>
            <td class="text-left"><?= htmlspecialchars($row['guru'] ?? '-'); ?></td>
            <td class="text-center"><?= htmlspecialchars($row['kelas']); ?></td>
            <td class="text-center"><?= htmlspecialchars($row['semester']); ?></td>
            <td class="text-center"><?= htmlspecialchars($row['tahun_ajaran']); ?></td>
            <td class="text-center number"><?= $nilai_harian; ?></td>
            <td class="text-center number"><?= $nilai_uts; ?></td>
            <td class="text-center number"><?= $nilai_uas; ?></td>
            <td class="text-center number <?= $nilai_akhir_class; ?>"><strong><?= $nilai_akhir; ?></strong></td>
            <td class="text-left"><?= htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
        </tr>
    <?php 
        endwhile;
    else:
    ?>
        <tr>
            <td colspan="12" class="no-data">
                Tidak ada data nilai ditemukan dalam database.
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="date-info">
    <p>Data diambil pada: <?php echo date('Y-m-d H:i:s'); ?></p>
    <p>Jumlah data: <?php echo $data ? $data->num_rows : 0; ?> record</p>
</div>

<div class="footer">
    <div class="ttd-section">
        <p>&nbsp;</p>
        <p>Mengetahui,</p>
        <div class="ttd-space"></div>
        <div class="ttd-line"></div>
        <p><strong>Kepala Sekolah</strong></p>
    </div>
</div>

<div class="no-print" style="position: fixed; top: 10px; right: 10px; background: #f8f9fa; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    <p>Dokumen ini akan dicetak secara otomatis.</p>
    <p>Jika tidak otomatis, tekan Ctrl+P</p>
    <button onclick="window.print()">Cetak Sekarang</button>
    <button onclick="window.close()">Tutup</button>
</div>

</body>
</html>