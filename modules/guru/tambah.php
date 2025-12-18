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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_guru     = $_POST['nama_guru'];
    $npwp          = $_POST['npwp'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat        = $_POST['alamat'];
    $guru_pengampu = $_POST['guru_pengampu'];
    $jabatan       = $_POST['jabatan'];
    $no_hp         = $_POST['no_hp'];

    $sql = "INSERT INTO guru (
                nama_guru, npwp, tanggal_lahir, alamat, 
                guru_pengampu, jabatan, no_hp
            ) VALUES (
                '$nama_guru', '$npwp', '$tanggal_lahir', '$alamat',
                '$guru_pengampu', '$jabatan', '$no_hp'
            )";

    if ($koneksi->query($sql)) {
        header('Location: index.php');
        exit();
    } else {
        $error = 'Gagal menambah data: ' . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Data Guru</h4>
        </div>

        <div class="card-body">
            
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Nama Guru</label>
                            <input type="text" name="nama_guru" class="form-control" placeholder="Nama lengkap guru" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NPWP</label>
                            <input type="text" name="npwp" class="form-control" placeholder="Nomor NPWP (boleh kosong)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap"></textarea>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Guru Pengampu</label>
                            <input type="text" name="guru_pengampu" class="form-control" placeholder="Contoh: masukkan ID">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Wali Kelas, Guru Tetap, dll">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Nomor handphone">
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
