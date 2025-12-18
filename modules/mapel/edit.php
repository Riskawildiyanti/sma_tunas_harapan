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

// CEK ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Mapel tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

$id_mapel = intval($_GET['id']);

// AMBIL DATA MAPEL BERDASARKAN ID
$sql = "SELECT * FROM mapel WHERE id_mapel = $id_mapel";
$result = $koneksi->query($sql);

if ($result->num_rows == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

$data = $result->fetch_assoc();

// AMBIL DATA GURU UNTUK DROPDOWN
$guruList = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");

// =========================
// PROSES UPDATE DATA
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode_mapel   = $_POST['kode_mapel'];
    $nama_mapel   = $_POST['nama_mapel'];
    $kategori     = $_POST['kategori'];
    $tingkat      = $_POST['tingkat'];
    $jurusan      = $_POST['jurusan'];
    $id_guru      = $_POST['id_guru']; // opsional
    $jam_pelajaran = $_POST['jam_pelajaran'];
    $deskripsi    = $_POST['deskripsi'];

    $sqlUpdate = "UPDATE mapel SET
                    kode_mapel = '$kode_mapel',
                    nama_mapel = '$nama_mapel',
                    kategori = '$kategori',
                    tingkat = '$tingkat',
                    jurusan = '$jurusan',
                    id_guru = " . ($id_guru ? "'$id_guru'" : "NULL") . ",
                    jam_pelajaran = '$jam_pelajaran',
                    deskripsi = '$deskripsi'
                  WHERE id_mapel = $id_mapel";

    if ($koneksi->query($sqlUpdate)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal mengupdate data: " . $koneksi->error;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mapel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4 class="mb-0 text-white">Edit Mata Pelajaran</h4>
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
                            <input type="text" name="kode_mapel" class="form-control" required 
                                   value="<?= htmlspecialchars($data['kode_mapel']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Mapel</label>
                            <input type="text" name="nama_mapel" class="form-control" required
                                   value="<?= htmlspecialchars($data['nama_mapel']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control"
                                   value="<?= htmlspecialchars($data['kategori']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tingkat</label>
                            <select name="tingkat" class="form-select" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="X" <?= ($data['tingkat'] == 'X') ? 'selected' : '' ?>>X</option>
                                <option value="XI" <?= ($data['tingkat'] == 'XI') ? 'selected' : '' ?>>XI</option>
                                <option value="XII" <?= ($data['tingkat'] == 'XII') ? 'selected' : '' ?>>XII</option>
                            </select>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control"
                                   value="<?= htmlspecialchars($data['jurusan']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guru Pengampu</label>
                            <select name="id_guru" class="form-select">
                                <option value="">-- Pilih Guru --</option>
                                <?php while ($g = $guruList->fetch_assoc()): ?>
                                    <option value="<?= $g['id_guru']; ?>"
                                        <?= ($data['id_guru'] == $g['id_guru']) ? 'selected' : '' ?>>
                                        <?= $g['nama_guru']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam Pelajaran</label>
                            <input type="number" name="jam_pelajaran" class="form-control"
                                   value="<?= htmlspecialchars($data['jam_pelajaran']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn btn-primary">💾 Update</button>
                <a href="index.php" class="btn btn-secondary">⬅ Kembali</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
