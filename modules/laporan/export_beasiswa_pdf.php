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

/* =======================
   QUERY DATA BEASISWA
======================= */
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
            persyaratan,
            status
        FROM beasiswa
        ORDER BY nama_beasiswa ASC";

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
body { font-family: Arial, sans-serif; font-size: 11px; }
h2 { text-align: center; margin-bottom: 5px; }
p { text-align: center; margin-top: 0; }
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
.text-center { text-align: center; }
</style>
</head>
<body>

<h2>LAPORAN DATA BEASISWA</h2>
<p>SMA TUNAS HARAPAN</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>ID Beasiswa</th>
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
<tbody>';

$no = 1;

if ($data && $data->num_rows > 0) {
    while ($row = $data->fetch_assoc()) {
        $html .= '
        <tr>
            <td class="text-center">'.$no++.'</td>
            <td class="text-center">'.$row['id_beasiswa'].'</td>
            <td>'.$row['nama_beasiswa'].'</td>
            <td>'.$row['nama_penerima'].'</td>
            <td>'.$row['penyedia'].'</td>
            <td class="text-center">'.$row['jenis'].'</td>
            <td class="text-center">Rp '.number_format($row['nominal'],0,",",".").'</td>
            <td class="text-center">'.$row['periode'].'</td>
            <td class="text-center">'.$row['tanggal_mulai'].'</td>
            <td class="text-center">'.$row['tanggal_selesai'].'</td>
            <td class="text-center">'.$row['kuota'].'</td>
            <td class="text-center">'.$row['persyaratan'].'</td>
            <td class="text-center"><b>'.$row['status'].'</b></td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="12" class="text-center">Data beasiswa tidak tersedia</td>
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
    "Laporan_Beasiswa.pdf",
    ["Attachment" => false]
);
