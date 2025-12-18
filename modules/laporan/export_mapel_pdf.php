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
   QUERY DATA MAPEL
======================= */
$sql = "SELECT
            m.id_mapel,
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
    vertical-align: top;
}
th {
    background-color: #eee;
    text-align: center;
}
.text-center { text-align: center; }
</style>
</head>
<body>

<h2>LAPORAN DATA MATA PELAJARAN</h2>
<p>SMA TUNAS HARAPAN</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>ID</th>
    <th>Kode Mapel</th>
    <th>Nama Mapel</th>
    <th>Kategori</th>
    <th>Tingkat</th>
    <th>Jurusan</th>
    <th>Guru Pengampu</th>
    <th>Jam Pelajaran</th>
    <th>Deskripsi</th>
</tr>
</thead>
<tbody>';

$no = 1;

if ($data && $data->num_rows > 0) {
    while ($row = $data->fetch_assoc()) {
        $html .= '
        <tr>
            <td class="text-center">'.$no++.'</td>
            <td class="text-center">'.$row['id_mapel'].'</td>
            <td class="text-center">'.$row['kode_mapel'].'</td>
            <td>'.$row['nama_mapel'].'</td>
            <td class="text-center">'.$row['kategori'].'</td>
            <td class="text-center">'.$row['tingkat'].'</td>
            <td>'.$row['jurusan'].'</td>
            <td>'.$row['nama_guru'].'</td>
            <td class="text-center">'.$row['jam_pelajaran'].'</td>
            <td>'.$row['deskripsi'].'</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="10" class="text-center">Data mata pelajaran tidak tersedia</td>
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
    "Laporan_Data_Mapel.pdf",
    ["Attachment" => false]
);
