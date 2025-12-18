<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Menu Laporan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📊 Menu Laporan Sistem</h4>

            <!-- Tombol Kembali ke Dashboard -->
            <a href="../../dashboard.php" class="btn btn-light btn-sm">
                ⬅ Kembali ke Dashboard
            </a>
        </div>

        <div class="card-body">

            <p class="text-muted">Silakan pilih salah satu menu laporan di bawah ini:</p>

            <div class="row g-3">

                <!-- LAPORAN SISWA -->
                <div class="col-md-4">
                    <a href="siswa.php" class="text-decoration-none">
                        <div class="card p-3 text-center shadow-sm">
                            <h5>👨‍🎓 Laporan Data Siswa</h5>
                        </div>
                    </a>
                </div>

                <!-- LAPORAN GURU -->
                <div class="col-md-4">
                    <a href="guru.php" class="text-decoration-none">
                        <div class="card p-3 text-center shadow-sm">
                            <h5>👨‍🏫 Laporan Data Guru</h5>
                        </div>
                    </a>
                </div>

                <!-- LAPORAN MAPEL -->
                <div class="col-md-4">
                    <a href="mapel.php" class="text-decoration-none">
                        <div class="card p-3 text-center shadow-sm">
                            <h5>📘 Laporan Data Mapel</h5>
                        </div>
                    </a>
                </div>

                <!-- LAPORAN KELAS -->
                <div class="col-md-4">
                    <a href="kelas.php" class="text-decoration-none">
                        <div class="card p-3 text-center shadow-sm">
                            <h5>🏫 Laporan Data Kelas</h5>
                        </div>
                    </a>
                </div>

                <!-- LAPORAN BEASISWA -->
                <div class="col-md-4">
                    <a href="beasiswa.php" class="text-decoration-none">
                        <div class="card p-3 text-center shadow-sm">
                            <h5>🎓 Laporan Data Beasiswa</h5>
                        </div>
                    </a>
                </div>

                <!-- LAPORAN NILAI -->
                <div class="col-md-4">
                    <a href="nilai.php" class="text-decoration-none">
                        <div class="card p-3 text-center shadow-sm">
                            <h5>📄 Laporan Data Nilai</h5>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>

</body>
</html>
