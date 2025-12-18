<?php
$host = "localhost";
$dbname = "sma_tunhar";  
$user = "root";
$pass = "";

try {
    $koneksi = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("❌ Koneksi gagal: " . $e->getMessage());
}


// Jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'] ?? '';
    $email_admin = $_POST['email_admin'] ?? '';
    $phone_admin = $_POST['phone_admin'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $timezone = $_POST['timezone'] ?? 'Asia/Jakarta';
    $theme_color = $_POST['theme_color'] ?? 'primary';
    $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;

    // Upload logo jika ada
    $site_logo = $settings['site_logo'] ?? null;
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $filename = 'uploads/' . basename($_FILES['site_logo']['name']);
        move_uploaded_file($_FILES['site_logo']['tmp_name'], $filename);
        $site_logo = $filename;
    }

    try {
        if ($settings) {
            // Update
            $stmt = $koneksi->prepare("UPDATE pengaturan SET 
                site_name=:site_name,
                site_logo=:site_logo,
                email_admin=:email_admin,
                phone_admin=:phone_admin,
                alamat=:alamat,
                timezone=:timezone,
                theme_color=:theme_color,
                maintenance_mode=:maintenance_mode,
                updated_at=NOW()
                WHERE id=:id
            ");
            $stmt->execute([
                ':site_name'=>$site_name,
                ':site_logo'=>$site_logo,
                ':email_admin'=>$email_admin,
                ':phone_admin'=>$phone_admin,
                ':alamat'=>$alamat,
                ':timezone'=>$timezone,
                ':theme_color'=>$theme_color,
                ':maintenance_mode'=>$maintenance_mode,
                ':id'=>$settings['id']
            ]);
        } else {
            // Insert
            $stmt = $koneksi->prepare("INSERT INTO pengaturan
                (site_name, site_logo, email_admin, phone_admin, alamat, timezone, theme_color, maintenance_mode)
                VALUES (:site_name, :site_logo, :email_admin, :phone_admin, :alamat, :timezone, :theme_color, :maintenance_mode)
            ");
            $stmt->execute([
                ':site_name'=>$site_name,
                ':site_logo'=>$site_logo,
                ':email_admin'=>$email_admin,
                ':phone_admin'=>$phone_admin,
                ':alamat'=>$alamat,
                ':timezone'=>$timezone,
                ':theme_color'=>$theme_color,
                ':maintenance_mode'=>$maintenance_mode
            ]);
        }
        $success = "Pengaturan berhasil disimpan!";
        // Refresh data
        $stmt = $koneksi->prepare("SELECT * FROM pengaturan LIMIT 1");
        $stmt->execute();
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "❌ Gagal menyimpan pengaturan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Pengaturan Sistem</h2>

    <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="site_name" class="form-label">Nama Sistem</label>
            <input type="text" class="form-control" name="site_name" id="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="site_logo" class="form-label">Logo Sistem</label>
            <input type="file" class="form-control" name="site_logo" id="site_logo">
            <?php if(!empty($settings['site_logo'])): ?>
                <img src="<?= $settings['site_logo'] ?>" alt="Logo" style="max-height:80px;margin-top:10px;">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="email_admin" class="form-label">Email Admin</label>
            <input type="email" class="form-control" name="email_admin" id="email_admin" value="<?= htmlspecialchars($settings['email_admin'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="phone_admin" class="form-label">Nomor Telepon Admin</label>
            <input type="text" class="form-control" name="phone_admin" id="phone_admin" value="<?= htmlspecialchars($settings['phone_admin'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" id="alamat"><?= htmlspecialchars($settings['alamat'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="timezone" class="form-label">Zona Waktu</label>
            <input type="text" class="form-control" name="timezone" id="timezone" value="<?= htmlspecialchars($settings['timezone'] ?? 'Asia/Jakarta') ?>">
        </div>

        <div class="mb-3">
            <label for="theme_color" class="form-label">Warna Tema</label>
            <select class="form-control" name="theme_color" id="theme_color">
                <?php 
                $colors = ['primary','success','danger','warning','info','dark'];
                foreach($colors as $color): ?>
                    <option value="<?= $color ?>" <?= ($settings['theme_color'] ?? '')==$color?'selected':'' ?>><?= ucfirst($color) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode" <?= !empty($settings['maintenance_mode'])?'checked':'' ?>>
            <label class="form-check-label" for="maintenance_mode">Mode Maintenance</label>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
    </form>
</div>
</body>
</html>
