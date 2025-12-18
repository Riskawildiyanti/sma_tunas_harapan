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

$id = intval($_GET['id']); // amankan input

// HAPUS DATA BEASISWA (Prepared Statement)
$stmt = $koneksi->prepare("DELETE FROM beasiswa WHERE id_beasiswa = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: index.php');
    exit();
} else {
    echo "<div class='alert alert-danger text-center mt-3'>Gagal menghapus: " . $koneksi->error . "</div>";
}
?>
