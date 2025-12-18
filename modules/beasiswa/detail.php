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

// AMANKAN INPUT ID
$id = intval($_GET['id']);

// AMBIL DATA BEASISWA
$stmt = $koneksi->prepare("SELECT * FROM beasiswa WHERE id_beasiswa = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) die("<h3 class='text-danger text-center mt-5'>Data beasiswa tidak ditemukan!</h3>");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">🎓 Detail Beasiswa</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <?php 
                    foreach ($data as $key => $value) : 
                        // Agar kolom tampilan rapi, ubah underscore menjadi spasi
                        $label = htmlspecialchars(str_replace('_', ' ', $key));
                    ?>
                    <tr>
                        <th class="text-capitalize bg-light" style="width: 30%;"><?= $label ?></th>
                        <td><?= nl2br(htmlspecialchars($value)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="mt-3">
                <a href="edit.php?id=<?= $data['id_beasiswa']; ?>" class="btn btn-warning">✏ Edit Data</a>
                <a href="index.php" class="btn btn-secondary">⬅ Kembali</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
