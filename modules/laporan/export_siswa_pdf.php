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

<h2>LAPORAN DATA SISWA</h2>
<p>SMA TUNAS HARAPAN</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>ID</th>
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
    <th>Alamat Orang Tua</th>
    <th>No Telepon Ortu</th>
    <th>Kelas</th>
    <th>Status</th>
    <th>Tahun Masuk</th>
</tr>
</thead>
<tbody>';

$no = 1;

if ($data && $data->num_rows > 0) {
    while ($row = $data->fetch_assoc()) {
        $tanggal_lahir = date('d-m-Y', strtotime($row['tanggal_lahir']));
        $html .= '
        <tr>
            <td class="text-center">'.$no++.'</td>
            <td class="text-center">'.$row['id_siswa'].'</td>
            <td class="text-center">'.$row['nis'].'</td>
            <td class="text-center">'.$row['nisn'].'</td>
            <td>'.$row['nama_lengkap'].'</td>
            <td class="text-center">'.$row['jenis_kelamin'].'</td>
            <td>'.$row['tempat_lahir'].', '.$tanggal_lahir.'</td>
            <td class="text-center">'.$row['agama'].'</td>
            <td>'.$row['alamat'].'</td>
            <td>'.$row['no_telepon'].'</td>
            <td>'.$row['email'].'</td>
            <td>'.$row['nama_ayah'].'</td>
            <td>'.$row['pekerjaan_ayah'].'</td>
            <td>'.$row['nama_ibu'].'</td>
            <td>'.$row['pekerjaan_ibu'].'</td>
            <td>'.$row['alamat_ortu'].'</td>
            <td>'.$row['no_telepon_ortu'].'</td>
            <td class="text-center">'.$row['nama_kelas'].'</td>
            <td class="text-center">'.$row['status'].'</td>
            <td class="text-center">'.$row['tahun_masuk'].'</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="20" class="text-center">Data siswa tidak tersedia</td>
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
    "Laporan_Data_Siswa.pdf",
    ["Attachment" => false]
);
