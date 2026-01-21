<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_siswa     = $_POST['nama_siswa'];
    $mata_pelajaran = $_POST['mata_pelajaran'];
    $guru           = $_POST['guru'];
    $kelas          = $_POST['kelas'];
    $semester       = $_POST['semester'];
    $tahun_ajaran   = $_POST['tahun_ajaran'];
    $nilai_harian   = $_POST['nilai_harian'] ?? 0;
    $nilai_uts      = $_POST['nilai_uts'] ?? 0;
    $nilai_uas      = $_POST['nilai_uas'] ?? 0;
    $keterangan     = $_POST['keterangan'];

    // HITUNG NILAI AKHIR
    $nilai_akhir = ($nilai_harian * 0.4) + ($nilai_uts * 0.3) + ($nilai_uas * 0.3);

    $sql = "INSERT INTO nilai (
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
            ) VALUES (
                '$nama_siswa',
                '$mata_pelajaran',
                '$guru',
                '$kelas',
                '$semester',
                '$tahun_ajaran',
                '$nilai_harian',
                '$nilai_uts',
                '$nilai_uas',
                '$nilai_akhir',
                '$keterangan'
            )";

    if ($koneksi->query($sql)) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Gagal menambah data: " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Nilai Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Nilai Siswa</h4>
        </div>

        <div class="card-body">
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" name="nama_siswa" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guru</label>
                            <input type="text" name="guru" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="2024/2025" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nilai Harian</label>
                            <input type="number" step="0.01" name="nilai_harian" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nilai UTS</label>
                            <input type="number" step="0.01" name="nilai_uts" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nilai UAS</label>
                            <input type="number" step="0.01" name="nilai_uas" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control"></textarea>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn btn-success">💾 Simpan</button>
                <a href="index.php" class="btn btn-secondary">⬅ Kembali</a>

            </form>
        </div>
    </div>

</div>

</body>
</html>
