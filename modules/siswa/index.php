<?php
// modules/siswa/index.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();
if (!$koneksi) die("❌ Error: Tidak dapat terhubung ke database");

// AMBIL DATA SISWA
$sql = "SELECT * FROM siswa ORDER BY nama_lengkap ASC";
$result = $koneksi->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - SMA TUNAS HARAPAN</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #eef2f7; }
        .navbar-brand { font-weight: 600; letter-spacing: .5px; }
        .table thead th { white-space: nowrap; }
        .btn-action:hover { transform: scale(1.12); }
        .btn-action { transition: 0.2s ease-in-out; }
        .card { border-radius: 12px; }
        .table { border-radius: 12px; overflow: hidden; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-primary bg-primary shadow-sm py-3">
    <div class="container-fluid">
        <span class="navbar-brand text-white"><i class="fas fa-school me-2"></i>SMA TUNAS HARAPAN</span>
        <a href="../../logout.php" class="btn btn-light btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="container py-4">

    <!-- CARD CONTAINER -->
    <div class="card shadow border-0">

        <!-- HEADER DALAM CARD -->
        <div class="card-header bg-white border-0 pt-4">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../../dashboard.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Siswa</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <h4 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>Data Siswa</h4>
                    <small class="text-muted">Manajemen data siswa SMA Tunas Harapan</small>
                </div>
                <a href="tambah.php" class="btn btn-primary shadow-sm">
                    <i class="fas fa-user-plus me-1"></i>Tambah Siswa
                </a>
            </div>
        </div>

        <!-- CARD BODY -->
        <div class="card-body">

            <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover align-middle" id="siswaTable">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>NIS / NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>No Telepon</th>
                            <th>Status</th>
                            <th>Tahun Masuk</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; foreach ($result as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <b><?= htmlspecialchars($row['nis']); ?></b><br>
                                <small class="text-muted"><?= htmlspecialchars($row['nisn']); ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?= ($row['jenis_kelamin']=='Laki-laki')?'Laki-laki':'Perempuan'; ?></td>
                            <td><?= htmlspecialchars($row['no_telepon'] ?: '-'); ?></td>
                            <td>
                                <?php
                                    $badge = [
                                        'aktif'=>'success',
                                        'alumni'=>'info',
                                        'pindah'=>'warning',
                                        'keluar'=>'danger'
                                    ];
                                    $st = $row['status'] ?? 'aktif';
                                ?>
                                <span class="badge bg-<?= $badge[$st] ?? 'secondary'; ?> px-3 py-2">
                                    <?= ucfirst($st); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['tahun_masuk'] ?: '-'); ?></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $row['id_siswa']; ?>" class="btn btn-info btn-sm btn-action" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="edit.php?id=<?= $row['id_siswa']; ?>" class="btn btn-warning btn-sm btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                                <a onclick="return confirm('Yakin ingin menghapus siswa <?= $row['nama_lengkap']; ?>?')" href="hapus.php?id=<?= $row['id_siswa']; ?>" class="btn btn-danger btn-sm btn-action" title="Hapus"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <h3>Belum Ada Data</h3>
                    <p class="text-muted">Silakan tambahkan siswa baru</p>
                    <a href="tambah.php" class="btn btn-primary"><i class="fas fa-user-plus me-1"></i>Tambah Siswa</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#siswaTable').DataTable();
    });
</script>

</body>
</html>
<?php $koneksi->close(); 
?>

