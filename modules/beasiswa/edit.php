<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

// CEK ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// AMBIL DATA BEASISWA
$stmt = $koneksi->prepare("SELECT * FROM beasiswa WHERE id_beasiswa = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("<h3 class='text-center text-danger mt-5'>Data beasiswa tidak ditemukan!</h3>");
}

// UPDATE DATA BEASISWA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_beasiswa = $_POST['nama_beasiswa'];
    $nama_penerima = $_POST['nama_penerima']; // DITAMBAHKAN
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

    $update = $koneksi->prepare("
        UPDATE beasiswa SET
            nama_beasiswa = ?, 
            nama_penerima = ?,     -- DITAMBAHKAN
            penyedia = ?, 
            jenis = ?, 
            nominal = ?, 
            periode = ?, 
            tanggal_mulai = ?, 
            tanggal_selesai = ?, 
            kuota = ?, 
            deskripsi = ?, 
            persyaratan = ?, 
            status = ?
        WHERE id_beasiswa = ?
    ");

    $update->bind_param(
        "ssssssssssssi",
        $nama_beasiswa,
        $nama_penerima,     // DITAMBAHKAN
        $penyedia,
        $jenis,
        $nominal,
        $periode,
        $tanggal_mulai,
        $tanggal_selesai,
        $kuota,
        $deskripsi,
        $persyaratan,
        $status,
        $id
    );

    if ($update->execute()) {
        header("Location: index.php");
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
    <title>Edit Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">🎓 Edit Data Beasiswa</h4>
        </div>
        <div class="card-body">

            <form method="POST" class="row g-3">

                <!-- NAMA BEASISWA -->
                <div class="col-md-12">
                    <label class="form-label">Nama Beasiswa</label>
                    <input type="text" class="form-control" name="nama_beasiswa" value="<?= htmlspecialchars($data['nama_beasiswa']); ?>" required>
                </div>

                <!-- NAMA PENERIMA (Baru) -->
                <div class="col-md-12">
                    <label class="form-label">Nama Penerima</label>
                    <input type="text" class="form-control" name="nama_penerima" value="<?= htmlspecialchars($data['nama_penerima']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Penyedia</label>
                    <input type="text" class="form-control" name="penyedia" value="<?= htmlspecialchars($data['penyedia']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis</label>
                    <input type="text" class="form-control" name="jenis" value="<?= htmlspecialchars($data['jenis']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nominal</label>
                    <input type="number" class="form-control" name="nominal" value="<?= htmlspecialchars($data['nominal']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Periode</label>
                    <input type="text" class="form-control" name="periode" value="<?= htmlspecialchars($data['periode']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="tanggal_mulai" value="<?= $data['tanggal_mulai']; ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" name="tanggal_selesai" value="<?= $data['tanggal_selesai']; ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kuota</label>
                    <input type="number" class="form-control" name="kuota" value="<?= htmlspecialchars($data['kuota']); ?>">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="2"><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Persyaratan</label>
                    <textarea class="form-control" name="persyaratan" rows="2"><?= htmlspecialchars($data['persyaratan']); ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="Aktif" <?= ($data['status']=='Aktif'?'selected':'') ?>>Aktif</option>
                        <option value="Tidak Aktif" <?= ($data['status']=='Tidak Aktif'?'selected':'') ?>>Tidak Aktif</option>
                    </select>
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
