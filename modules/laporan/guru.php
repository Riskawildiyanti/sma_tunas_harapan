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
            id_guru,
            nama_guru,
            npwp,
            tanggal_lahir,
            alamat,
            guru_pengampu,
            jabatan,
            no_hp
        FROM guru
        ORDER BY nama_guru ASC";

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Guru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📘 Laporan Data Guru</h4>
        </div>

        <div class="card-body">

            <!-- Tombol Aksi -->
            <a href="export_guru_excel.php" class="btn btn-success btn-sm">📥 Export Excel</a>
            <a href="export_guru_pdf.php" class="btn btn-danger btn-sm">📄 Export PDF</a>
            <a href="cetak_guru.php" target="_blank" class="btn btn-info btn-sm">🖨 Cetak</a>
            <a href="index.php" class="btn btn-secondary btn-sm">🔙 Kembali</a>

            <!-- Tabel -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>ID Guru</th>
                            <th>Nama Guru</th>
                            <th>NPWP</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Mata Pelajaran</th>
                            <th>Jabatan</th>
                            <th>No HP</th>
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
                            <td><?= htmlspecialchars($row['id_guru']); ?></td>
                            <td><?= htmlspecialchars($row['nama_guru']); ?></td>
                            <td><?= htmlspecialchars($row['npwp']); ?></td>
                            <td class="text-center">
                                <?= $row['tanggal_lahir'] 
                                    ? date('d-m-Y', strtotime($row['tanggal_lahir'])) 
                                    : '-' ?>
                            </td>
                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                            <td><?= htmlspecialchars($row['guru_pengampu']); ?></td>
                            <td><?= htmlspecialchars($row['jabatan']); ?></td>
                            <td><?= htmlspecialchars($row['no_hp']); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                Tidak ada data guru ditemukan.
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
