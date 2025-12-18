<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

// AMBIL ID NILAI
$id = $_GET['id'];

// QUERY DETAIL NILAI LENGKAP
$sql = "
    SELECT n.*, 
           s.nama_lengkap AS nama_siswa,
           k.nama_kelas,
           m.nama_mapel,
           g.nama_guru
    FROM nilai n
    LEFT JOIN siswa s ON n.id_siswa = s.id_siswa
    LEFT JOIN kelas k ON n.id_kelas = k.id_kelas
    LEFT JOIN mapel m ON n.id_mapel = m.id_mapel
    LEFT JOIN guru g ON n.id_guru = g.id_guru
    WHERE n.id_nilai = '$id'
";

$data = $koneksi->query($sql)->fetch_assoc();

if (!$data) die("<h3 class='text-danger text-center mt-5'>Data nilai tidak ditemukan!</h3>");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Nilai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📄 Detail Nilai Siswa</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <tr>
                        <th style="width:30%">Nama Siswa</th>
                        <td><?= $data['nama_siswa']; ?></td>
                    </tr>

                    <tr>
                        <th>Kelas</th>
                        <td><?= $data['nama_kelas']; ?></td>
                    </tr>

                    <tr>
                        <th>Mata Pelajaran</th>
                        <td><?= $data['nama_mapel']; ?></td>
                    </tr>

                    <tr>
                        <th>Guru</th>
                        <td><?= $data['nama_guru']; ?></td>
                    </tr>

                    <tr>
                        <th>Semester</th>
                        <td><?= $data['semester']; ?></td>
                    </tr>

                    <tr>
                        <th>Tahun Ajaran</th>
                        <td><?= $data['tahun_ajaran']; ?></td>
                    </tr>

                    <tr>
                        <th>Nilai Harian</th>
                        <td><?= $data['nilai_harian']; ?></td>
                    </tr>

                    <tr>
                        <th>Nilai UTS</th>
                        <td><?= $data['nilai_uts']; ?></td>
                    </tr>

                    <tr>
                        <th>Nilai UAS</th>
                        <td><?= $data['nilai_uas']; ?></td>
                    </tr>

                    <tr>
                        <th>Nilai Akhir</th>
                        <td class="fw-bold text-primary"><?= $data['nilai_akhir']; ?></td>
                    </tr>

                    <tr>
                        <th>Keterangan</th>
                        <td><?= nl2br($data['keterangan']); ?></td>
                    </tr>

                </table>
            </div>

            <div class="mt-3">
                <a href="edit.php?id=<?= $data['id_nilai']; ?>" class="btn btn-warning">
                    ✏ Edit Data
                </a>
                <a href="index.php" class="btn btn-secondary">
                    ⬅ Kembali
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
