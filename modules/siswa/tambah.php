<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_siswa = $_POST['id_siswa'];
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

    $sql = "INSERT INTO siswa (
    id_siswa, nis, nisn, nama_lengkap, jenis_kelamin, tempat_lahir,
    tanggal_lahir, agama, alamat, no_telepon, email,
    nama_ayah, nama_ibu, pekerjaan_ayah, pekerjaan_ibu,
    alamat_ortu, no_telepon_ortu, id_kelas, status, tahun_masuk
) VALUES (
    NULL, '$nis', '$nisn', '$nama_lengkap', '$jenis_kelamin', '$tempat_lahir',
    '$tanggal_lahir', '$agama', '$alamat', '$no_telepon', '$email',
    '$nama_ayah', '$nama_ibu', '$pekerjaan_ayah', '$pekerjaan_ibu',
    '$alamat_ortu', '$no_telepon_ortu', '$id_kelas', '$status', '$tahun_masuk'
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
    <title>Tambah Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Data Siswa</h4>
        </div>

        <div class="card-body">
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agama</label>
                            <input type="text" name="agama" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control"></textarea>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="no_telepon" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Orang Tua</label>
                            <textarea name="alamat_ortu" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telepon Orang Tua</label>
                            <input type="text" name="no_telepon_ortu" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ID Kelas</label>
                            <input type="text" name="id_kelas" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="alumni">Alumni</option>
                                <option value="pindah">Pindah</option>
                                <option value="keluar">Keluar</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" class="form-control">
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
