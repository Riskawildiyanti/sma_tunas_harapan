<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

// AMBIL ID MAPEL
$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM mapel WHERE id_mapel='$id'")->fetch_assoc();

if (!$data) die("<h3 class='text-danger text-center mt-5'>Data tidak ditemukan!</h3>");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Mapel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">📄 Detail Mata Pelajaran</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <?php foreach($data as $key => $value): ?>
                    <tr>
                        <th class="text-capitalize bg-light" style="width: 30%;">
                            <?= str_replace('_', ' ', $key) ?>
                        </th>
                        <td><?= $value ?></td>
                    </tr>
                    <?php endforeach; ?>

                </table>
            </div>

            <div class="mt-3">
                <a href="edit.php?id=<?= $data['id_mapel']; ?>" class="btn btn-warning">
                    ✏ Edit Data
                </a>
                <a href="index.php" class="btn btn-secondary">
                    ⬅ Kembali
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
