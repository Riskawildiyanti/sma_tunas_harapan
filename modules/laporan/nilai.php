<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';

$database = new Database();
$koneksi = $database->getConnection();

$sql = "SELECT 
            n.id_nilai,
            s.nama_lengkap,
            k.nama_kelas,
            m.nama_mapel,
            n.semester,
            n.tahun_ajaran,
            n.nilai_harian,
            n.nilai_uts,
            n.nilai_uas,
            n.nilai_akhir,
            n.keterangan
        FROM nilai n
        JOIN siswa s ON n.id_siswa = s.id_siswa
        JOIN kelas k ON n.id_kelas = k.id_kelas
        JOIN mapel m ON n.id_mapel = m.id_mapel
        ORDER BY s.nama_lengkap ASC";

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Nilai</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid mt-4">
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📄 Laporan Data Nilai Siswa</h4>
        </div>

        <div class="card-body">

            <!-- Tombol Aksi -->
            <a href="export_nilai_excel.php" class="btn btn-success btn-sm">📥 Export Excel</a>
            <a href="export_nilai_pdf.php" class="btn btn-danger btn-sm">📄 Export PDF</a>
            <a href="cetak_nilai.php" target="_blank" class="btn btn-info btn-sm">🖨 Cetak</a>
            <a href="index.php" class="btn btn-secondary btn-sm">🔙 Kembali</a>

            <!-- Tabel -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Semester</th>
                            <th>Tahun Ajaran</th>
                            <th>Harian</th>
                            <th>UTS</th>
                            <th>UAS</th>
                            <th>Nilai Akhir</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php 
                    if ($data && $data->num_rows > 0):
                        $no = 1;
                        while ($row = $data->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                            <td><?= htmlspecialchars($row['nama_mapel']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['semester']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['tahun_ajaran']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['nilai_harian']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['nilai_uts']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['nilai_uas']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['nilai_akhir']); ?></td>
                            <td><?= htmlspecialchars($row['keterangan']); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="11" class="text-center text-danger">
                                Tidak ada data nilai ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
