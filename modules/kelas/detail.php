<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM kelas WHERE id_kelas='$id'")->fetch_assoc();

if (!$data) die("<h3 class='text-danger text-center mt-5'>Data tidak ditemukan!</h3>");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">📄 Detail Kelas</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <?php foreach ($data as $key => $value): ?>
                <tr>
                    <th class="bg-light text-capitalize" style="width: 30%;">
                        <?= str_replace('_', ' ', $key) ?>
                    </th>
                    <td><?= $value ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <a href="edit.php?id=<?= $data['id_kelas']; ?>" class="btn btn-warning">✏ Edit</a>
            <a href="index.php" class="btn btn-secondary">⬅ Kembali</a>
        </div>
    </div>
</div>

</body>
</html>
