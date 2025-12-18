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
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Mata Pelajaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid mt-4">
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📘 Laporan Data Mata Pelajaran</h4>
        </div>

        <div class="card-body">

            <!-- Tombol Aksi -->
            <a href="export_mapel_excel.php" class="btn btn-success btn-sm">📥 Export Excel</a>
            <a href="export_mapel_pdf.php" class="btn btn-danger btn-sm">📄 Export PDF</a>
            <a href="cetak_mapel.php" target="_blank" class="btn btn-info btn-sm">🖨 Cetak</a>
            <a href="index.php" class="btn btn-secondary btn-sm">🔙 Kembali</a>

            <!-- Tabel -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>ID Mapel</th>
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

                    <tbody>
                    <?php 
                    if ($data && $data->num_rows > 0):
                        $no = 1;
                        while ($row = $data->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['id_mapel']); ?></td>
                            <td><?= htmlspecialchars($row['kode_mapel']); ?></td>
                            <td><?= htmlspecialchars($row['nama_mapel']); ?></td>
                            <td><?= htmlspecialchars($row['kategori']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['tingkat']); ?></td>
                            <td><?= htmlspecialchars($row['jurusan']); ?></td>
                            <td><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['jam_pelajaran']); ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="9" class="text-center text-danger">
                                Tidak ada data mata pelajaran ditemukan.
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
