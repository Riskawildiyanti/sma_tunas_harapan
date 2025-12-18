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

$sql = "SELECT
            s.nis,
            s.nama_lengkap AS nama_siswa,
            k.nama_kelas,
            m.nama_mapel,
            g.nama_guru,
            n.semester,
            n.tahun_ajaran,
            n.nilai_harian,
            n.nilai_uts,
            n.nilai_uas,
            n.nilai_akhir,
            n.keterangan
        FROM nilai n
        INNER JOIN siswa s ON n.id_siswa = s.id_siswa
        INNER JOIN kelas k ON n.id_kelas = k.id_kelas
        INNER JOIN mapel m ON n.id_mapel = m.id_mapel
        LEFT JOIN guru g ON n.id_guru = g.id_guru
        ORDER BY s.nama_lengkap ASC, m.nama_mapel ASC";

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

<h2>LAPORAN NILAI SISWA</h2>
<p>SMA TUNAS HARAPAN</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>NIS</th>
    <th>Nama Siswa</th>
    <th>Kelas</th>
    <th>Mapel</th>
    <th>Guru</th>
    <th>Semester</th>
    <th>Tahun</th>
    <th>Harian</th>
    <th>UTS</th>
    <th>UAS</th>
    <th>Akhir</th>
    <th>Keretarangan</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($row = $data->fetch_assoc()) {
    $html .= '
    <tr>
        <td class="text-center">'.$no++.'</td>
        <td>'.$row['nis'].'</td>
        <td>'.$row['nama_siswa'].'</td>
        <td>'.$row['nama_kelas'].'</td>
        <td>'.$row['nama_mapel'].'</td>
        <td>'.($row['nama_guru'] ?? '-').'</td>
        <td class="text-center">'.$row['semester'].'</td>
        <td class="text-center">'.$row['tahun_ajaran'].'</td>
        <td class="text-center">'.$row['nilai_harian'].'</td>
        <td class="text-center">'.$row['nilai_uts'].'</td>
        <td class="text-center">'.$row['nilai_uas'].'</td>
        <td class="text-center"><b>'.$row['nilai_akhir'].'</b></td>
        <td class="text-center"><b>'.$row['keterangan'].'</b></td>
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
    "Laporan_Nilai_Siswa.pdf",
    ["Attachment" => false]
);
