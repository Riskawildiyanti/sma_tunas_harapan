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
   QUERY DATA KELAS
======================= */
$sql = "SELECT
            k.id_kelas,
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
    padding: 5px;
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

<h2>LAPORAN DATA KELAS</h2>
<p>SMA TUNAS HARAPAN</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>ID Kelas</th>
    <th>Kode Kelas</th>
    <th>Nama Kelas</th>
    <th>Tingkat</th>
    <th>Jurusan</th>
    <th>Wali Kelas</th>
    <th>Kapasitas</th>
    <th>Tahun Ajaran</th>
</tr>
</thead>
<tbody>';

$no = 1;

if ($data && $data->num_rows > 0) {
    while ($row = $data->fetch_assoc()) {
        $html .= '
        <tr>
            <td class="text-center">'.$no++.'</td>
            <td class="text-center">'.$row['id_kelas'].'</td>
            <td class="text-center">'.$row['kode_kelas'].'</td>
            <td>'.$row['nama_kelas'].'</td>
            <td class="text-center">'.$row['tingkat'].'</td>
            <td>'.$row['jurusan'].'</td>
            <td>'.$row['nama_guru'] ?? '-'.'</td>
            <td class="text-center">'.$row['kapasitas'].'</td>
            <td class="text-center">'.$row['tahun_ajaran'].'</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="9" class="text-center">Data kelas tidak tersedia</td>
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
    "Laporan_Data_Kelas.pdf",
    ["Attachment" => false]
);
