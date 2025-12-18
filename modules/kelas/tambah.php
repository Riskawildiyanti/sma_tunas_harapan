<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

$error = "";

// Ambil data guru untuk dropdown
$guruList = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode_kelas = $_POST['kode_kelas'];
    $nama_kelas = $_POST['nama_kelas'];
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];
    $id_guru = $_POST['id_guru']; // wali kelas
    $kapasitas = $_POST['kapasitas'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    // INSERT tanpa kolom yang tidak ada
    $sql = "INSERT INTO kelas (
                kode_kelas, nama_kelas, tingkat, jurusan, id_guru, kapasitas, tahun_ajaran
            ) VALUES (
                '$kode_kelas', '$nama_kelas', '$tingkat', '$jurusan', 
                " . ($id_guru ? "'$id_guru'" : "NULL") . ", 
                '$kapasitas', '$tahun_ajaran'
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
    <title>Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Data Kelas</h4>
        </div>

        <div class="card-body">
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Kode Kelas</label>
                            <input type="text" name="kode_kelas" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tingkat</label>
                            <select name="tingkat" class="form-select" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control">
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <select name="id_guru" class="form-select">
                                <option value="">-- Pilih Guru -- (Opsional)</option>
                                <?php while ($g = $guruList->fetch_assoc()): ?>
                                    <option value="<?= $g['id_guru']; ?>">
                                        <?= $g['nama_guru']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" name="kapasitas" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="2024/2025" required>
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
