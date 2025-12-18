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
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Kelas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid mt-4">
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">🏫 Laporan Data Kelas</h4>
        </div>

        <div class="card-body">

            <!-- Tombol Aksi -->
            <a href="export_kelas_excel.php" class="btn btn-success btn-sm">📥 Export Excel</a>
            <a href="export_kelas_pdf.php" class="btn btn-danger btn-sm">📄 Export PDF</a>
            <a href="cetak_kelas.php" target="_blank" class="btn btn-info btn-sm">🖨 Cetak</a>
            <a href="index.php" class="btn btn-secondary btn-sm">🔙 Kembali</a>

            <!-- Tabel -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark text-center align-middle">
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

                    <tbody>
                    <?php 
                    if ($data && $data->num_rows > 0):
                        $no = 1;
                        while ($row = $data->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['id_kelas']); ?></td>
                            <td><?= htmlspecialchars($row['kode_kelas']); ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['tingkat']); ?></td>
                            <td><?= htmlspecialchars($row['jurusan']); ?></td>
                            <td><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['kapasitas']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['tahun_ajaran']); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="9" class="text-center text-danger">
                                Tidak ada data kelas ditemukan.
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
