<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = intval($_GET['id']);
$data = $koneksi->query("SELECT * FROM siswa WHERE id_siswa='$id'")->fetch_assoc();

if (!$data) {
    die("<h3 class='text-center text-danger mt-5'>Data siswa tidak ditemukan!</h3>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $email = $_POST['email'];
    $nama_ayah = $_POST['nama_ayah'];
    $nama_ibu = $_POST['nama_ibu'];
    $pekerjaan_ayah = $_POST['pekerjaan_ayah'];
    $pekerjaan_ibu = $_POST['pekerjaan_ibu'];
    $alamat_ortu = $_POST['alamat_ortu'];
    $no_telepon_ortu = $_POST['no_telepon_ortu'];
    $id_kelas = $_POST['id_kelas'];
    $status = $_POST['status'];
    $tahun_masuk = $_POST['tahun_masuk'];

    $sql = "UPDATE siswa SET
            nis='$nis',
            nisn='$nisn',
            nama_lengkap='$nama_lengkap',
            jenis_kelamin='$jenis_kelamin',
            tempat_lahir='$tempat_lahir',
            tanggal_lahir='$tanggal_lahir',
            agama='$agama',
            alamat='$alamat',
            no_telepon='$no_telepon',
            email='$email',
            nama_ayah='$nama_ayah',
            nama_ibu='$nama_ibu',
            pekerjaan_ayah='$pekerjaan_ayah',
            pekerjaan_ibu='$pekerjaan_ibu',
            alamat_ortu='$alamat_ortu',
            no_telepon_ortu='$no_telepon_ortu',
            id_kelas='$id_kelas',
            status='$status',
            tahun_masuk='$tahun_masuk'
            WHERE id_siswa='$id'";

    if ($koneksi->query($sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Gagal update: {$koneksi->error}</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">✏ Edit Data Siswa</h4>
        </div>

        <div class="card-body">
            <form method="POST" class="row g-3">

                <div class="col-md-4">
                    <label>NIS</label>
                    <input type="text" class="form-control" name="nis" value="<?= $data['nis']; ?>" required>
                </div>

                <div class="col-md-4">
                    <label>NISN</label>
                    <input type="text" class="form-control" name="nisn" value="<?= $data['nisn']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" value="<?= $data['nama_lengkap']; ?>" required>
                </div>

                <div class="col-md-4">
                    <label>Jenis Kelamin</label>
                     <select name="jenis_kelamin" class="form-select">
                        <option value='Laki-laki' <?= ($data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value='Perempuan' <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control" name="tempat_lahir" value="<?= $data['tempat_lahir']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir" value="<?= $data['tanggal_lahir']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Agama</label>
                    <input type="text" class="form-control" name="agama" value="<?= $data['agama']; ?>">
                </div>

                <div class="col-md-8">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat"><?= $data['alamat']; ?></textarea>
                </div>

                <div class="col-md-4">
                    <label>No Telepon</label>
                    <input type="text" class="form-control" name="no_telepon" value="<?= $data['no_telepon']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $data['email']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Nama Ayah</label>
                    <input type="text" class="form-control" name="nama_ayah" value="<?= $data['nama_ayah']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Nama Ibu</label>
                    <input type="text" class="form-control" name="nama_ibu" value="<?= $data['nama_ibu']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Pekerjaan Ayah</label>
                    <input type="text" class="form-control" name="pekerjaan_ayah" value="<?= $data['pekerjaan_ayah']; ?>">
                </div>

                <div class="col-md-4">
                    <label>Pekerjaan Ibu</label>
                    <input type="text" class="form-control" name="pekerjaan_ibu" value="<?= $data['pekerjaan_ibu']; ?>">
                </div>

                <div class="col-md-8">
                    <label>Alamat Orang Tua</label>
                    <textarea class="form-control" name="alamat_ortu"><?= $data['alamat_ortu']; ?></textarea>
                </div>

                <div class="col-md-4">
                    <label>No Telepon Ortu</label>
                    <input type="text" class="form-control" name="no_telepon_ortu" value="<?= $data['no_telepon_ortu']; ?>">
                </div>

                <!-- Dropdown kelas -->
                <div class="col-md-4">
                    <label>Kelas</label>
                    <select name="id_kelas" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        $kelasQuery = $koneksi->query("SELECT * FROM kelas ORDER BY tingkat, nama_kelas");
                        while ($row = $kelasQuery->fetch_assoc()) {
                            $sel = ($data['id_kelas'] == $row['id_kelas']) ? "selected" : "";
                            echo "<option value='{$row['id_kelas']}' $sel>{$row['tingkat']} - {$row['nama_kelas']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" <?= ($data['status']=='aktif')?'selected':''; ?>>Aktif</option>
                        <option value="tamat" <?= ($data['status']=='tamat')?'selected':''; ?>>Tamat</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Tahun Masuk</label>
                    <input type="number" class="form-control" name="tahun_masuk" value="<?= $data['tahun_masuk']; ?>">
                </div>

                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-success px-4">💾 Update</button>
                    <a href="index.php" class="btn btn-secondary px-4">❌ Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>
