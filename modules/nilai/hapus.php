<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
    exit();
}

require_once '../../koneksi.php';
$database = new Database();
$koneksi = $database->getConnection();

// AMBIL ID NILAI
$id = $_GET['id'];

if ($koneksi->query("DELETE FROM nilai WHERE id_nilai='$id'")) {
    header('Location: index.php');
    exit();
} else {
    echo "Gagal menghapus nilai: " . $koneksi->error;
}
?>
