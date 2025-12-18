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

// Ambil data guru untuk dropdown pengampu
$guruList = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode_mapel   = $_POST['kode_mapel'];
    $nama_mapel   = $_POST['nama_mapel'];
    $kategori     = $_POST['kategori'];
    $tingkat      = $_POST['tingkat'];
    $jurusan      = $_POST['jurusan'];
    $id_guru      = $_POST['id_guru'];
    $jam_pelajaran = $_POST['jam_pelajaran'];
    $deskripsi    = $_POST['deskripsi'];

    // Query Insert sesuai struktur tabel MAPEL
    $sql = "INSERT INTO mapel (
                kode_mapel, nama_mapel, kategori, tingkat, jurusan,
                id_guru, jam_pelajaran, deskripsi
            ) VALUES (
                '$kode_mapel', '$nama_mapel', '$kategori', '$tingkat',
                '$jurusan', " . ($id_guru ? "'$id_guru'" : "NULL") . ",
                '$jam_pelajaran', '$deskripsi'
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
    <title>Tambah Mapel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Data Mata Pelajaran</h4>
        </div>

        <div class="card-body">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="row">
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Kode Mapel</label>
                            <input type="text" name="kode_mapel" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Mapel</label>
                            <input type="text" name="nama_mapel" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Contoh: Wajib, Umum, Peminatan">
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

                    </div>
                    
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" placeholder="IPA / IPS / AGAMA">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guru Pengampu</label>
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
                            <label class="form-label">Jam Pelajaran</label>
                            <input type="number" name="jam_pelajaran" class="form-control" placeholder="Contoh: 2">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
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
