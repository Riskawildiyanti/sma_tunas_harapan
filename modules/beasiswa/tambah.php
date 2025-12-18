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

    $nama_beasiswa = $_POST['nama_beasiswa'];
    $nama_penerima = $_POST['nama_penerima'];
    $penyedia = $_POST['penyedia'];
    $jenis = $_POST['jenis'];
    $nominal = $_POST['nominal'];
    $periode = $_POST['periode'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $kuota = $_POST['kuota'];
    $deskripsi = $_POST['deskripsi'];
    $persyaratan = $_POST['persyaratan'];
    $status = $_POST['status'];

    // Prepared statement tanpa dibuat_pada
    $sql = "INSERT INTO beasiswa (
                nama_beasiswa, nama_penerima, penyedia, jenis, nominal, periode,
                tanggal_mulai, tanggal_selesai, kuota, deskripsi, persyaratan, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $koneksi->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "ssssisssisss",
            $nama_beasiswa,
            $nama_penerima,
            $penyedia,
            $jenis,
            $nominal,
            $periode,
            $tanggal_mulai,
            $tanggal_selesai,
            $kuota,
            $deskripsi,
            $persyaratan,
            $status
        );

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $error = "Gagal menambah data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Error prepare statement: " . $koneksi->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Data Beasiswa</h4>
        </div>

        <div class="card-body">
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Nama Beasiswa</label>
                            <input type="text" name="nama_beasiswa" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="nama_penerima" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Penyedia</label>
                            <input type="text" name="penyedia" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <input type="text" name="jenis" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <input type="text" name="periode" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kuota</label>
                            <input type="number" name="kuota" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Persyaratan</label>
                            <textarea name="persyaratan" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn btn-success">💾 Simpan</button>
                <a href="index.php" class="btn btn-secondary">⬅ Kembali</a>

            </form>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>
