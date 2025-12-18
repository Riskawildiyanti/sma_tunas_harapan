<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once "../../koneksi.php";
$database = new Database();
$koneksi = $database->getConnection();
if (!$koneksi) die("❌ Koneksi database gagal!");

// CEK ID KELAS
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID kelas tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$id_kelas = intval($_GET['id']);

// AMBIL DATA KELAS
$stmt = $koneksi->prepare("SELECT * FROM kelas WHERE id_kelas = ?");
$stmt->bind_param("i", $id_kelas);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data kelas tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kode_kelas   = $_POST['kode_kelas'];
    $nama_kelas   = $_POST['nama_kelas'];
    $tingkat      = $_POST['tingkat'];
    $jurusan      = $_POST['jurusan'];
    $id_guru      = !empty($_POST['id_guru']) ? intval($_POST['id_guru']) : null;
    $kapasitas    = $_POST['kapasitas'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $queryUpdate = "UPDATE kelas SET 
        kode_kelas = ?, 
        nama_kelas = ?, 
        tingkat = ?, 
        jurusan = ?, 
        id_guru = ?, 
        kapasitas = ?, 
        tahun_ajaran = ?
        WHERE id_kelas = ?";

    $stmtUpdate = $koneksi->prepare($queryUpdate);
    $stmtUpdate->bind_param(
        "ssssissi",
        $kode_kelas,
        $nama_kelas,
        $tingkat,
        $jurusan,
        $id_guru,
        $kapasitas,
        $tahun_ajaran,
        $id_kelas
    );

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Data kelas berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . $stmtUpdate->error . "');</script>";
    }
}

// AMBIL LIST GURU
$listGuru = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0">✏ Edit Data Kelas</h4>
                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Kode Kelas</label>
                            <input type="text" name="kode_kelas" class="form-control" 
                                   value="<?= htmlspecialchars($data['kode_kelas']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control"
                                   value="<?= htmlspecialchars($data['nama_kelas']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tingkat</label>
                            <input type="text" name="tingkat" class="form-control"
                                   value="<?= htmlspecialchars($data['tingkat']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control"
                                   value="<?= htmlspecialchars($data['jurusan']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Wali Kelas (Guru)</label>
                            <select name="id_guru" class="form-select">
                                <option value="">-- Tidak Ada Wali Kelas --</option>
                                <?php while ($g = $listGuru->fetch_assoc()) { ?>
                                    <option value="<?= $g['id_guru']; ?>" 
                                        <?= ($data['id_guru'] == $g['id_guru']) ? 'selected' : ''; ?>>
                                        <?= $g['nama_guru']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kapasitas Kelas</label>
                            <input type="number" name="kapasitas" class="form-control"
                                   value="<?= htmlspecialchars($data['kapasitas']); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control"
                                   value="<?= htmlspecialchars($data['tahun_ajaran']); ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">⬅ Kembali</a>
                            <button type="submit" class="btn btn-primary">💾 Update Data</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
