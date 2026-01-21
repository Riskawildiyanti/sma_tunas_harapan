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

// Query disesuaikan dengan field yang diberikan
$sql = "SELECT 
            id_nilai,
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

$data = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Nilai</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        padding-top: 20px;
    }
    .card-header {
        background: linear-gradient(135deg, #1a2980, #26d0ce);
        border-bottom: 0;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,.125);
    }
    .table th {
        background-color: #2c3e50;
        color: white;
        border-color: #34495e;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        padding: 12px 8px;
        font-size: 14px;
    }
    .table td {
        padding: 10px 8px;
        font-size: 13.5px;
        vertical-align: middle;
    }
    .btn {
        border-radius: 6px;
        padding: 8px 16px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s;
    }
    .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
    }
    .btn-success:hover {
        background: linear-gradient(135deg, #218838, #1ba87e);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }
    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        border: none;
    }
    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333, #d63384);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }
    .btn-info {
        background: linear-gradient(135deg, #17a2b8, #20c997);
        border: none;
    }
    .btn-info:hover {
        background: linear-gradient(135deg, #138496, #1ba87e);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
    }
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        border: none;
    }
    .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268, #343a40);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(26, 41, 128, 0.08);
    }
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
    .text-muted {
        color: #6c757d !important;
    }
    .fw-bold {
        color: #1a2980;
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn {
            margin-bottom: 5px;
            width: 100%;
        }
        .table th, 
        .table td {
            padding: 8px 5px;
            font-size: 12px;
        }
    }
</style>
</head>

<body class="bg-light">

<div class="container-fluid mt-3">
    <div class="card shadow-lg">

        <div class="card-header text-white">
            <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Laporan Data Nilai Siswa</h4>
        </div>

        <div class="card-body p-4">

            <!-- Tombol Aksi -->
            <div class="mb-4 d-flex flex-wrap gap-2">
                <a href="export_nilai_excel.php" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="export_nilai_pdf.php" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
                <a href="cetak_nilai.php" target="_blank" class="btn btn-info">
                    <i class="fas fa-print me-1"></i> Cetak
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Tabel -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th width="80">Semester</th>
                            <th width="100">Tahun Ajaran</th>
                            <th width="80">Nilai Harian</th>
                            <th width="80">Nilai UTS</th>
                            <th width="80">Nilai UAS</th>
                            <th width="100">Nilai Akhir</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php 
                    if ($data && $data->num_rows > 0):
                        $no = 1;
                        while ($row = $data->fetch_assoc()):
                            // Format nilai dengan 2 desimal sesuai field DECIMAL(5,2)
                            $nilai_harian = $row['nilai_harian'] !== null ? number_format((float)$row['nilai_harian'], 2) : '-';
                            $nilai_uts = $row['nilai_uts'] !== null ? number_format((float)$row['nilai_uts'], 2) : '-';
                            $nilai_uas = $row['nilai_uas'] !== null ? number_format((float)$row['nilai_uas'], 2) : '-';
                            $nilai_akhir = $row['nilai_akhir'] !== null ? number_format((float)$row['nilai_akhir'], 2) : '-';
                            
                            // Warna untuk nilai akhir
                            $nilai_akhir_class = '';
                            if ($row['nilai_akhir'] !== null) {
                                $nilai = (float)$row['nilai_akhir'];
                                if ($nilai >= 80) {
                                    $nilai_akhir_class = 'text-success';
                                } elseif ($nilai >= 60) {
                                    $nilai_akhir_class = 'text-warning';
                                } else {
                                    $nilai_akhir_class = 'text-danger';
                                }
                            }
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                            <td><?= htmlspecialchars($row['mata_pelajaran']); ?></td>
                            <td><?= htmlspecialchars($row['guru'] ?? '-'); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['kelas']); ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?= $row['semester'] == 'Ganjil' ? 'primary' : 'info'; ?>">
                                    <?= htmlspecialchars($row['semester']); ?>
                                </span>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($row['tahun_ajaran']); ?></td>
                            <td class="text-center"><?= $nilai_harian; ?></td>
                            <td class="text-center"><?= $nilai_uts; ?></td>
                            <td class="text-center"><?= $nilai_uas; ?></td>
                            <td class="text-center fw-bold <?= $nilai_akhir_class; ?>"><?= $nilai_akhir; ?></td>
                            <td><?= htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="12" class="text-center text-muted py-5">
                                <i class="fas fa-database fa-3x mb-3 d-block text-secondary"></i>
                                <h5 class="mb-2">Tidak ada data nilai ditemukan</h5>
                                <p class="mb-0">Silakan tambahkan data nilai terlebih dahulu</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>
        
        <!-- Footer Tabel -->
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i> 
                        Total Data: <strong><?= $data ? $data->num_rows : 0; ?></strong> record
                        <?php if ($data && $data->num_rows > 0): ?>
                            | Ditampilkan: <strong><?= $no-1; ?></strong> data
                        <?php endif; ?>
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Dicetak: <?= date('d/m/Y H:i:s'); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Menambahkan efek hover yang lebih baik
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('.table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                this.style.transition = 'all 0.3s';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>

</body>
</html>