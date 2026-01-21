<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../../koneksi.php';

$database = new Database();
$koneksi = $database->getConnection();

if (!$koneksi) {
    die("Koneksi database gagal");
}

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

$result = $koneksi->query($sql);

if (!$result) {
    die("Query gagal: " . $koneksi->error);
}

// Header untuk download Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"laporan_nilai_" . date('Y-m-d') . ".xls\"");
header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .number {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        .header-info {
            text-align: center;
            margin-bottom: 15px;
        }
        .header-info h2 {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="header-info">
    <h2>LAPORAN DATA NILAI SISWA</h2>
    <h3>SISTEM INFORMASI SEKOLAH</h3>
    <p>Tanggal Export: <?php echo date('d F Y H:i:s'); ?></p>
</div>

<table border="1">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Nama Siswa</th>
            <th width="15%">Mata Pelajaran</th>
            <th width="12%">Guru</th>
            <th width="8%">Kelas</th>
            <th width="8%">Semester</th>
            <th width="10%">Tahun Ajaran</th>
            <th width="7%">Nilai Harian</th>
            <th width="7%">Nilai UTS</th>
            <th width="7%">Nilai UAS</th>
            <th width="8%">Nilai Akhir</th>
            <th width="15%">Keterangan</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            // Format nilai dengan 2 desimal
            $nilai_harian = $row['nilai_harian'] !== null ? number_format((float)$row['nilai_harian'], 2) : '-';
            $nilai_uts = $row['nilai_uts'] !== null ? number_format((float)$row['nilai_uts'], 2) : '-';
            $nilai_uas = $row['nilai_uas'] !== null ? number_format((float)$row['nilai_uas'], 2) : '-';
            $nilai_akhir = $row['nilai_akhir'] !== null ? number_format((float)$row['nilai_akhir'], 2) : '-';
            
            // Handle NULL values
            $guru = $row['guru'] !== null ? htmlspecialchars($row['guru']) : '-';
            $keterangan = $row['keterangan'] !== null ? htmlspecialchars($row['keterangan']) : '-';
            
            echo "<tr>
                    <td align='center'>{$no}</td>
                    <td>" . htmlspecialchars($row['nama_siswa']) . "</td>
                    <td>" . htmlspecialchars($row['mata_pelajaran']) . "</td>
                    <td>{$guru}</td>
                    <td align='center'>" . htmlspecialchars($row['kelas']) . "</td>
                    <td align='center'>" . htmlspecialchars($row['semester']) . "</td>
                    <td align='center'>" . htmlspecialchars($row['tahun_ajaran']) . "</td>
                    <td align='center' class='number'>{$nilai_harian}</td>
                    <td align='center' class='number'>{$nilai_uts}</td>
                    <td align='center' class='number'>{$nilai_uas}</td>
                    <td align='center' class='number'><strong>{$nilai_akhir}</strong></td>
                    <td>{$keterangan}</td>
                  </tr>";
            $no++;
        }
        
        // Menambahkan footer summary
        echo "<tr>
                <td colspan='12' align='center' style='font-weight: bold; background-color: #f2f2f2;'>
                    Total Data: " . ($no - 1) . " record | Exported on: " . date('d-m-Y H:i:s') . "
                </td>
              </tr>";
    } else {
        echo "<tr>
                <td colspan='12' align='center' style='color: #999; font-style: italic;'>
                    Tidak ada data nilai ditemukan dalam database.
                </td>
              </tr>";
    }
    ?>
    </tbody>
</table>

</body>
</html>