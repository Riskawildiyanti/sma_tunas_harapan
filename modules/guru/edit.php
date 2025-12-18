<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

// CEK ID GURU DI URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID guru tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

$id = intval($_GET['id']);

// AMBIL DATA GURU
$sql = "SELECT * FROM guru WHERE id_guru = ?";
$stmt = $koneksi->prepare($sql);

if (!$stmt) {
    die("Query error: " . $koneksi->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("<h3 class='text-center text-danger mt-5'>Data guru tidak ditemukan!</h3>");
}

// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_guru          = $_POST['nama_guru'];
    $npwp               = $_POST['npwp'];
    $tanggal_lahir      = $_POST['tanggal_lahir'];
    $alamat             = $_POST['alamat'];
    $guru_pengampu   = $_POST['guru_pengampu'];
    $jabatan            = $_POST['jabatan'];
    $no_hp              = $_POST['no_hp'];

    $update = $koneksi->prepare("
        UPDATE guru SET
            nama_guru = ?,
            npwp = ?,
            tanggal_lahir = ?,
            alamat = ?,
            guru_pengampu = ?,
            jabatan = ?,
            no_hp = ?
        WHERE id_guru = ?
    ");

    if (!$update) {
        die("Query error UPDATE: " . $koneksi->error);
    }

    $update->bind_param(
        "sssssssi",
        $nama_guru,
        $npwp,
        $tanggal_lahir,
        $alamat,
        $guru_pengampu,
        $jabatan,
        $no_hp,
        $id
    );

    if ($update->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Gagal update: " . $koneksi->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">👨‍🏫 Edit Data Guru</h4>
        </div>
        <div class="card-body">

            <form method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Guru</label>
                    <input type="text" class="form-control" name="nama_guru" value="<?= htmlspecialchars($data['nama_guru']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">NPWP</label>
                    <input type="text" class="form-control" name="npwp" value="<?= htmlspecialchars($data['npwp']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir" value="<?= $data['tanggal_lahir']; ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Guru Pengampu</label>
                    <input type="text" class="form-control" name="id_guru" value="<?= htmlspecialchars($data['id_guru']); ?>">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat"><?= htmlspecialchars($data['alamat']); ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" value="<?= htmlspecialchars($data['jabatan']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" class="form-control" name="no_hp" value="<?= htmlspecialchars($data['no_hp']); ?>">
                </div>

                <div class="col-12 mt-4 text-center">
                    <button type="submit" class="btn btn-success px-4">💾 Update Data</button>
                    <a href="index.php" class="btn btn-secondary px-4">❌ Batal</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>
