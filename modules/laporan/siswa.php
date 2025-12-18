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
            s.status,
            s.tahun_masuk,
            k.nama_kelas
        FROM siswa s
        LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
        ORDER BY s.nama_lengkap ASC";

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Siswa Lengkap</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid mt-4">
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📘 Laporan Data Siswa Lengkap</h4>
        </div>

        <div class="card-body">

            <!-- Tombol Aksi -->
            <a href="export_siswa_excel.php" class="btn btn-success btn-sm">📥 Export Excel</a>
            <a href="export_siswa_pdf.php" class="btn btn-danger btn-sm">📄 Export PDF</a>
            <a href="cetak_siswa.php" target="_blank" class="btn btn-info btn-sm">🖨 Cetak</a>
            <a href="index.php" class="btn btn-secondary btn-sm">🔙 Kembali</a>

            <!-- Tabel -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>JK</th>
                            <th>TTL</th>
                            <th>Agama</th>
                            <th>Kelas</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Email</th>
                            <th>Nama Ayah</th>
                            <th>Nama Ibu</th>
                            <th>Pekerjaan Ayah</th>
                            <th>Pekerjaan Ibu</th>
                            <th>Alamat Ortu</th>
                            <th>HP Ortu</th>
                            <th>Status</th>
                            <th>Tahun Masuk</th>
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
                            <td><?= htmlspecialchars($row['nis']); ?></td>
                            <td><?= htmlspecialchars($row['nisn']); ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                            <td><?= htmlspecialchars($row['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($row['tanggal_lahir']))); ?></td>
                            <td><?= htmlspecialchars($row['agama']); ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                            <td><?= htmlspecialchars($row['no_telepon']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['nama_ayah']); ?></td>
                            <td><?= htmlspecialchars($row['nama_ibu']); ?></td>
                            <td><?= htmlspecialchars($row['pekerjaan_ayah']); ?></td>
                            <td><?= htmlspecialchars($row['pekerjaan_ibu']); ?></td>
                            <td><?= htmlspecialchars($row['alamat_ortu']); ?></td>
                            <td><?= htmlspecialchars($row['no_telepon_ortu']); ?></td>
                            <td><?= htmlspecialchars($row['status']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['tahun_masuk']); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="19" class="text-center text-danger">
                                Tidak ada data siswa ditemukan.
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
