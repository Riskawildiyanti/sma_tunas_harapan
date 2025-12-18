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

if ($koneksi->query("DELETE FROM kelas WHERE id_kelas='$id'")) {
    header("Location: index.php");
    exit();
} else {
    echo "Gagal menghapus data kelas: " . $koneksi->error;
}
?>
