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
            deskripsi,
            persyaratan,
            status
        FROM beasiswa
        ORDER BY nama_beasiswa ASC";

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Beasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid mt-4">
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">🎓 Laporan Data Beasiswa</h4>
        </div>

        <div class="card-body">

            <!-- Tombol Aksi -->
            <a href="export_beasiswa_excel.php" class="btn btn-success btn-sm">📥 Export Excel</a>
            <a href="export_beasiswa_pdf.php" class="btn btn-danger btn-sm">📄 Export PDF</a>
            <a href="cetak_beasiswa.php" target="_blank" class="btn btn-info btn-sm">🖨 Cetak</a>
            <a href="index.php" class="btn btn-secondary btn-sm">🔙 Kembali</a>

            <!-- Tabel -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>ID Beasiswa</th>
                            <th>Nama Beasiswa</th>
                            <th>Nama Penerima</th>
                            <th>Penyedia</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Periode</th>
                            <th>Periode Berlaku</th>
                            <th>Kuota</th>
                            <th>Persyaratan</th>
                            <th>Status</th>
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
                            <td><?= htmlspecialchars($row['id_beasiswa']); ?></td>
                            <td><?= htmlspecialchars($row['nama_beasiswa']); ?></td>
                            <td><?= htmlspecialchars($row['nama_penerima']); ?></td>
                            <td><?= htmlspecialchars($row['penyedia']); ?></td>
                            <td><?= htmlspecialchars($row['jenis']); ?></td>
                            <td class="text-end">
                                Rp <?= number_format($row['nominal'], 0, ',', '.'); ?>
                            </td>
                            <td><?= htmlspecialchars($row['periode']); ?></td>
                            <td class="text-center">
                                <?= $row['tanggal_mulai'] && $row['tanggal_selesai']
                                    ? date('d-m-Y', strtotime($row['tanggal_mulai'])) . ' s/d ' . date('d-m-Y', strtotime($row['tanggal_selesai']))
                                    : '-' ?>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($row['kuota']); ?></td>
                            <td><?= htmlspecialchars($row['persyaratan']); ?></td>
                            <td class="text-center">
                                <span class="badge <?= $row['status'] === 'aktif' ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="11" class="text-center text-danger">
                                Tidak ada data beasiswa ditemukan.
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
