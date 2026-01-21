<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
require_once '../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
        ORDER BY nama_siswa ASC, mata_pelajaran ASC";

$data = $koneksi->query($sql);

/* =======================
   HTML PDF
======================= */
$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page {
        size: A4 landscape;
        margin: 15mm;
    }
    body { 
        font-family: "Helvetica", Arial, sans-serif; 
        font-size: 10px; 
        line-height: 1.4;
        margin: 0;
        padding: 0;
    }
    .header {
        text-align: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #333;
    }
    h1 { 
        font-size: 20px; 
        margin: 5px 0; 
        color: #1a2980;
    }
    h3 { 
        font-size: 14px; 
        margin: 3px 0; 
        color: #666;
    }
    .info {
        text-align: center;
        margin-bottom: 15px;
    }
    .info p {
        margin: 2px 0;
        font-size: 11px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 9px;
    }
    th, td {
        border: 1px solid #333;
        padding: 6px 4px;
        text-align: center;
        vertical-align: middle;
    }
    th {
        background-color: #1a2980;
        color: white;
        font-weight: bold;
        font-size: 10px;
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
        font-family: "Courier New", monospace;
        font-weight: normal;
    }
    .nilai-akhir {
        font-weight: bold;
    }
    .footer {
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
        font-size: 9px;
        color: #666;
    }
    .ttd {
        float: right;
        width: 250px;
        text-align: center;
    }
    .ttd-line {
        border-top: 1px solid #000;
        width: 150px;
        margin: 40px auto 5px;
    }
    .summary {
        background-color: #f8f9fa;
        padding: 8px;
        margin-top: 15px;
        font-size: 10px;
        border: 1px solid #dee2e6;
    }
</style>
</head>
<body>

<div class="header">
    <h1>LAPORAN DATA NILAI SISWA</h1>
    <h3>SMA TUNAS HARAPAN</h3>
</div>

<table>
<thead>
<tr>
    <th width="4%">No</th>
    <th width="16%">Nama Siswa</th>
    <th width="14%">Mata Pelajaran</th>
    <th width="12%">Guru</th>
    <th width="8%">Kelas</th>
    <th width="8%">Semester</th>
    <th width="8%">Tahun Ajaran</th>
    <th width="6%">Nilai Harian</th>
    <th width="6%">Nilai UTS</th>
    <th width="6%">Nilai UAS</th>
    <th width="8%">Nilai Akhir</th>
    <th width="14%">Keterangan</th>
</tr>
</thead>
<tbody>';

$no = 1;
$total_data = 0;
if ($data && $data->num_rows > 0) {
    $total_data = $data->num_rows;
    while ($row = $data->fetch_assoc()) {
        // Format nilai dengan 2 desimal
        $nilai_harian = $row['nilai_harian'] !== null ? number_format((float)$row['nilai_harian'], 2) : '-';
        $nilai_uts = $row['nilai_uts'] !== null ? number_format((float)$row['nilai_uts'], 2) : '-';
        $nilai_uas = $row['nilai_uas'] !== null ? number_format((float)$row['nilai_uas'], 2) : '-';
        $nilai_akhir = $row['nilai_akhir'] !== null ? number_format((float)$row['nilai_akhir'], 2) : '-';
        
        // Warna untuk nilai akhir
        $nilai_akhir_class = '';
        if ($row['nilai_akhir'] !== null) {
            $nilai = (float)$row['nilai_akhir'];
            if ($nilai >= 80) {
                $nilai_akhir_class = 'text-success';
            } elseif ($nilai >= 60) {
                $nilai_akhir_class = 'text-warning';
            } else {
                $nilai_akhir_class = 'text-danger';
            }
        }
        
        // Handle NULL values
        $guru = $row['guru'] !== null ? htmlspecialchars($row['guru']) : '-';
        $keterangan = $row['keterangan'] !== null ? htmlspecialchars($row['keterangan']) : '-';
        
        $html .= '
        <tr>
            <td class="text-center">' . $no++ . '</td>
            <td class="text-left">' . htmlspecialchars($row['nama_siswa']) . '</td>
            <td class="text-left">' . htmlspecialchars($row['mata_pelajaran']) . '</td>
            <td class="text-left">' . $guru . '</td>
            <td class="text-center">' . htmlspecialchars($row['kelas']) . '</td>
            <td class="text-center">' . htmlspecialchars($row['semester']) . '</td>
            <td class="text-center">' . htmlspecialchars($row['tahun_ajaran']) . '</td>
            <td class="text-center number">' . $nilai_harian . '</td>
            <td class="text-center number">' . $nilai_uts . '</td>
            <td class="text-center number">' . $nilai_uas . '</td>
            <td class="text-center number nilai-akhir ' . $nilai_akhir_class . '">' . $nilai_akhir . '</td>
            <td class="text-left">' . $keterangan . '</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="12" class="text-center" style="padding: 30px; color: #999;">
            <i>Tidak ada data nilai ditemukan dalam database.</i>
        </td>
    </tr>';
}

$html .= '
</tbody>
</table>

<div class="summary">
    Total Data: <strong>' . $total_data . '</strong> record | Dicetak pada: ' . date('d/m/Y H:i:s') . '
</div>

<div class="footer">
    <div class="ttd">
        <p>Mengetahui,</p>
        <div class="ttd-line"></div>
        <p><strong>Kepala Sekolah</strong></p>
    </div>
</div>

</body>
</html>';

/* =======================
   GENERATE PDF
======================= */
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->setPaper('A4', 'landscape');
$dompdf->loadHtml($html);
$dompdf->render();

// Output PDF
$dompdf->stream(
    "Laporan_Nilai_" . date('Y-m-d') . ".pdf",
    [
        "Attachment" => true // true untuk download, false untuk preview
    ]
);