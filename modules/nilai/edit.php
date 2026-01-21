<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

$id = $_GET['id'] ?? null;
if (!$id) {
    die("<h3 class='text-center text-danger mt-5'>ID tidak valid!</h3>");
}

$data = $koneksi->query("SELECT * FROM nilai WHERE id_nilai='$id'")->fetch_assoc();

if (!$data) {
    die("<h3 class='text-center text-danger mt-5'>Data nilai tidak ditemukan!</h3>");
}

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

    $sql = "UPDATE nilai SET
                nama_siswa     = '$nama_siswa',
                mata_pelajaran = '$mata_pelajaran',
                guru           = '$guru',
                kelas          = '$kelas',
                semester       = '$semester',
                tahun_ajaran   = '$tahun_ajaran',
                nilai_harian   = '$nilai_harian',
                nilai_uts      = '$nilai_uts',
                nilai_uas      = '$nilai_uas',
                nilai_akhir    = '$nilai_akhir',
                keterangan     = '$keterangan'
            WHERE id_nilai = '$id'";

    if ($koneksi->query($sql)) {
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
    <title>Edit Nilai Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">✏ Edit Nilai Siswa</h4>
        </div>

        <div class="card-body">
            <form method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Siswa</label>
                    <input type="text" class="form-control" name="nama_siswa"
                           value="<?= htmlspecialchars($data['nama_siswa']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" class="form-control" name="mata_pelajaran"
                           value="<?= htmlspecialchars($data['mata_pelajaran']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Guru</label>
                    <input type="text" class="form-control" name="guru"
                           value="<?= htmlspecialchars($data['guru']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kelas</label>
                    <input type="text" class="form-control" name="kelas"
                           value="<?= htmlspecialchars($data['kelas']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="Ganjil" <?= ($data['semester']=='Ganjil')?'selected':''; ?>>Ganjil</option>
                        <option value="Genap" <?= ($data['semester']=='Genap')?'selected':''; ?>>Genap</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" class="form-control" name="tahun_ajaran"
                           value="<?= htmlspecialchars($data['tahun_ajaran']); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nilai Harian</label>
                    <input type="number" step="0.01" class="form-control" name="nilai_harian"
                           value="<?= $data['nilai_harian']; ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nilai UTS</label>
                    <input type="number" step="0.01" class="form-control" name="nilai_uts"
                           value="<?= $data['nilai_uts']; ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nilai UAS</label>
                    <input type="number" step="0.01" class="form-control" name="nilai_uas"
                           value="<?= $data['nilai_uas']; ?>">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Keterangan</label>
                    <textarea class="form-control" name="keterangan"><?= htmlspecialchars($data['keterangan']); ?></textarea>
                </div>

                <div class="col-12 mt-4 text-center">
                    <button type="submit" class="btn btn-success px-4">💾 Update Nilai</button>
                    <a href="index.php" class="btn btn-secondary px-4">❌ Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>
