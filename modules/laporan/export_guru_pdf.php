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

/* =======================
   HTML PDF
======================= */
$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body {
    font-family: Arial, sans-serif;
    font-size: 11px;
}
h2 {
    text-align: center;
    margin-bottom: 5px;
}
p {
    text-align: center;
    margin-top: 0;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #000;
    padding: 4px;
}
th {
    background-color: #eee;
    text-align: center;
}
.text-center {
    text-align: center;
}
</style>
</head>
<body>

<h2>LAPORAN DATA GURU</h2>
<p>SMA TUNAS HARAPAN</p>

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
<tbody>';

$no = 1;

if ($data && $data->num_rows > 0) {
    while ($row = $data->fetch_assoc()) {
        $html .= '
        <tr>
            <td class="text-center">'.$no++.'</td>
            <td class="text-center">'.$row['id_guru'].'</td>
            <td>'.$row['nama_guru'].'</td>
            <td class="text-center">'.($row['npwp'] ?? '-').'</td>
            <td class="text-center">'.($row['tanggal_lahir'] ?? '-').'</td>
            <td>'.($row['alamat'] ?? '-').'</td>
            <td>'.($row['guru_pengampu'] ?? '-').'</td>
            <td class="text-center">'.($row['jabatan'] ?? '-').'</td>
            <td class="text-center">'.($row['no_hp'] ?? '-').'</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="9" class="text-center">Data guru tidak tersedia</td>
    </tr>';
}

$html .= '
</tbody>
</table>

</body>
</html>';

/* =======================
   GENERATE PDF
======================= */
$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'landscape');
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream(
    "Laporan_Guru.pdf",
    ["Attachment" => false]
);
