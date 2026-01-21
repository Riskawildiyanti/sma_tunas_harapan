<?php
session_start();
require_once 'koneksi.php';

$db = new Database();
$conn = $db->getConnection();

// Cek apakah user sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$total_siswa = 0;
$total_guru = 0;
$total_kelas = 0;
$total_mapel = 0;
$total_pengguna = 0;
$siswa_baru_hari_ini = 0;
$aktifitas_terbaru = [];

if ($conn) {
    // Total Siswa
    $sql = "SELECT COUNT(*) as total FROM siswa";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $total_siswa = (int)($row['total'] ?? 0);
    }
    
    // Total Guru
    $sql = "SELECT COUNT(*) as total FROM guru";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $total_guru = (int)($row['total'] ?? 0);
    }
    
    // Total Kelas
    $sql = "SELECT COUNT(*) as total FROM kelas";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $total_kelas = (int)($row['total'] ?? 0);
    }
    
    // Total Mata Pelajaran
    $sql = "SELECT COUNT(*) as total FROM mata_pelajaran";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $total_mapel = (int)($row['total'] ?? 0);
    }
    
    // Total Pengguna/Admin
    $sql = "SELECT COUNT(*) as total FROM admin";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $total_pengguna = (int)($row['total'] ?? 0);
    }
    
    // Siswa Baru Hari Ini
    $sql = "SELECT COUNT(*) as total FROM siswa WHERE DATE(tanggal_daftar) = CURDATE()";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $siswa_baru_hari_ini = (int)($row['total'] ?? 0);
    }
    
    // Aktivitas Terbaru
    $sql = "SELECT id_siswa, nama_siswa, tanggal_daftar FROM siswa ORDER BY tanggal_daftar DESC LIMIT 5";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $aktifitas_terbaru[] = $row;
        }
    }
    
    // Log Aktivitas
    $admin_id = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;
    if ($admin_id > 0) {
        $stmt = $conn->prepare("INSERT INTO log_aktivitas (admin_id, aktivitas, waktu) VALUES (?, ?, NOW())");
        if ($stmt) {
            $aktivitas = 'Login ke sistem';
            $stmt->bind_param('is', $admin_id, $aktivitas);
            $stmt->execute();
            $stmt->close();
        }
    }
}

date_default_timezone_set('Asia/Jakarta');
$hari_ini = date('l, d F Y');
$jam_sekarang = date('H:i:s');

$nama_user = $_SESSION['nama_lengkap'] ?? 'Administrator';
$user_level = $_SESSION['level'] ?? 'admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary: #1a2980;
            --secondary: #26d0ce;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
        }
        
        * { margin:0; padding:0; box-sizing:border-box; }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color:#f5f7fb; 
            min-height:100vh; 
        }
        
        .sidebar { 
            background: #1a2980; 
            color: white; 
            min-height: 100vh; 
            position: fixed; 
            width: 220px; 
            box-shadow: 3px 0 10px rgba(0,0,0,0.1); 
            transition: all .3s; 
            z-index: 1000; 
            padding-top: 20px;
        }
        
        .user-info { 
            padding: 20px 15px; 
            text-align: center; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            margin-bottom: 10px;
        }
        
        .user-avatar { 
            width: 50px; 
            height: 50px; 
            background: rgba(255,255,255,0.15); 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 10px; 
        }
        
        .user-avatar i { 
            font-size: 22px; 
            color: white;
        }
        
        .user-name { 
            font-size: 15px; 
            font-weight: 600; 
            margin-bottom: 5px; 
        }
        
        .user-role { 
            font-size: 12px; 
            opacity: 0.9; 
            background: rgba(255,255,255,0.1); 
            padding: 3px 10px; 
            border-radius: 15px; 
            display: inline-block; 
        }
        
        .nav-menu { 
            padding: 0 10px; 
        }
        
        .nav-item { 
            list-style: none; 
            margin-bottom: 5px;
        }
        
        .nav-link { 
            display: flex; 
            align-items: center; 
            padding: 12px 15px; 
            color: rgba(255,255,255,0.85); 
            text-decoration: none; 
            transition: all .3s; 
            border-radius: 8px;
            font-size: 14px;
        }
        
        .nav-link:hover { 
            background: rgba(255,255,255,0.1); 
            color: white; 
        }
        
        .nav-link.active { 
            background: rgba(255,255,255,0.15); 
            color: white; 
            font-weight: 500;
        }
        
        .nav-link i { 
            width: 22px; 
            margin-right: 12px; 
            text-align: center; 
            font-size: 16px;
        }
        
        .main-content { 
            margin-left: 220px; 
            padding: 20px; 
            transition: all .3s; 
        }
        
        .topbar { 
            background: white; 
            padding: 18px 25px; 
            border-radius: 12px; 
            margin-bottom: 25px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        
        .topbar-title h1 { 
            color: var(--primary); 
            font-size: 22px; 
            font-weight: 600; 
            margin-bottom: 5px; 
        }
        
        .topbar-title p { 
            color: #666; 
            font-size: 14px; 
            margin-bottom: 0; 
        }
        
        .date-time { 
            text-align: right; 
        }
        
        .date-time .date { 
            font-size: 15px; 
            font-weight: 600; 
            color: var(--primary); 
        }
        
        .date-time .time { 
            font-size: 13px; 
            color: #666; 
        }
        
        .stat-cards { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
            gap: 18px; 
            margin-bottom: 30px; 
        }
        
        .stat-card { 
            background: white; 
            border-radius: 12px; 
            padding: 22px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            transition: all .3s; 
            border: 1px solid #f0f0f0; 
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        
        .stat-icon { 
            width: 55px; 
            height: 55px; 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 18px; 
            font-size: 22px; 
            color: white; 
        }
        
        .stat-icon.student { 
            background: linear-gradient(135deg, #667eea, #764ba2); 
        }
        
        .stat-icon.teacher { 
            background: linear-gradient(135deg, #f093fb, #f5576c); 
        }
        
        .stat-icon.class { 
            background: linear-gradient(135deg, #4facfe, #00f2fe); 
        }
        
        .stat-icon.user { 
            background: linear-gradient(135deg, #ff9a9e, #fad0c4); 
        }
        
        .stat-title { 
            font-size: 13px; 
            color: #666; 
            margin-bottom: 5px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        
        .stat-value { 
            font-size: 28px; 
            font-weight: 700; 
            color: var(--primary); 
            margin-bottom: 10px; 
        }
        
        .stat-change { 
            font-size: 11px; 
            padding: 4px 9px; 
            border-radius: 8px; 
            display: inline-block; 
        }
        
        .stat-change.positive { 
            background: rgba(40,167,69,0.1); 
            color: var(--success); 
        }
        
        .quick-actions { 
            background: white; 
            border-radius: 12px; 
            padding: 22px; 
            margin-bottom: 30px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
        }
        
        .section-title { 
            color: var(--primary); 
            font-size: 18px; 
            font-weight: 600; 
            margin-bottom: 18px; 
            padding-bottom: 10px; 
            border-bottom: 2px solid #f0f0f0; 
            display: flex;
            align-items: center;
        }
        
        .action-buttons { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); 
            gap: 15px; 
        }
        
        .action-btn { 
            background: #f8f9fa; 
            border: 1px solid #e9ecef; 
            border-radius: 10px; 
            padding: 15px; 
            text-align: center; 
            color: #495057; 
            text-decoration: none; 
            transition: all .3s; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        
        .action-btn:hover { 
            background: var(--primary); 
            color: white; 
            border-color: var(--primary); 
            transform: translateY(-3px); 
            text-decoration: none; 
        }
        
        .action-btn i { 
            font-size: 22px; 
            margin-bottom: 10px; 
        }
        
        .action-btn span { 
            font-size: 13px; 
            font-weight: 500; 
        }
        
        .recent-activity { 
            background: white; 
            border-radius: 12px; 
            padding: 22px; 
            margin-bottom: 30px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
        }
        
        .activity-list { 
            list-style: none; 
            padding: 0; 
        }
        
        .activity-item { 
            display: flex; 
            align-items: center; 
            padding: 14px 0; 
            border-bottom: 1px solid #f0f0f0; 
        }
        
        .activity-item:last-child { 
            border-bottom: none; 
        }
        
        .activity-icon { 
            width: 38px; 
            height: 38px; 
            border-radius: 10px; 
            background: #f0f7ff; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-right: 15px; 
            color: var(--primary); 
            font-size: 14px;
        }
        
        .activity-content h5 { 
            font-size: 13px; 
            margin-bottom: 4px; 
            color: #333; 
            font-weight: 600;
        }
        
        .activity-content p { 
            font-size: 12px; 
            color: #666; 
            margin-bottom: 0; 
        }
        
        .activity-time { 
            font-size: 11px; 
            color: #999; 
            margin-left: auto;
            white-space: nowrap;
        }
        
        .system-info { 
            background: white; 
            border-radius: 12px; 
            padding: 22px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
        }
        
        .info-item { 
            display: flex; 
            align-items: center; 
            margin-bottom: 15px; 
        }
        
        .info-item:last-child { 
            margin-bottom: 0; 
        }
        
        .info-icon { 
            width: 38px; 
            height: 38px; 
            border-radius: 10px; 
            background: #f0f7ff; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-right: 15px; 
            color: var(--primary); 
            font-size: 14px;
        }
        
        .footer { 
            text-align: center; 
            padding: 20px; 
            color: #666; 
            font-size: 13px; 
            margin-top: 30px; 
            border-top: 1px solid #eee; 
        }
        
        .mobile-toggle { 
            display: none; 
            position: fixed; 
            top: 15px; 
            left: 15px; 
            z-index: 1001; 
            background: var(--primary); 
            color: white; 
            border: none; 
            width: 40px; 
            height: 40px; 
            border-radius: 10px; 
            font-size: 18px; 
        }
        
        .logout-btn { 
            background: rgba(255,255,255,0.1); 
            color: white; 
            border: 1px solid rgba(255,255,255,0.2); 
            padding: 7px 14px; 
            border-radius: 6px; 
            font-size: 13px; 
            margin-top: 10px; 
            transition: all .3s; 
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        
        .logout-btn:hover { 
            background: rgba(255,255,255,0.2); 
            color: white; 
            text-decoration: none; 
        }
        
        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 10px 15px;
        }
        
        .menu-group {
            margin-bottom: 15px;
        }
        
        .menu-group-title {
            color: rgba(255,255,255,0.6);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 15px;
            margin-bottom: 5px;
        }
        
        @media (max-width: 992px) {
            .sidebar { 
                margin-left: -220px; 
            }
            .sidebar.active { 
                margin-left: 0; 
            }
            .main-content { 
                margin-left: 0; 
                padding-top: 70px;
            }
            .mobile-toggle { 
                display: flex; 
                align-items: center; 
                justify-content: center; 
            }
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .date-time {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <h5 class="user-name"><?php echo htmlspecialchars($nama_user); ?></h5>
            <div class="user-role"><?php echo htmlspecialchars(ucfirst($user_level)); ?></div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <div class="menu-divider"></div>
            
            <div class="menu-group">
                <div class="menu-group-title">Data </div>
                <li class="nav-item">
                    <a href="modules/siswa/index.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/guru/index.php" class="nav-link">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Data Guru</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/kelas/index.php" class="nav-link">
                        <i class="fas fa-school"></i>
                        <span>Data Kelas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/mapel/index.php" class="nav-link">
                        <i class="fas fa-book"></i>
                        <span>Mata Pelajaran</span>
                    </a>
                </li>
            </div>
            
            <div class="menu-divider"></div>
            
            <div class="menu-group">
                <div class="menu-group-title">Akademik</div>
                <li class="nav-item">
                    <a href="modules/nilai/index.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Nilai Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/beasiswa/index.php" class="nav-link">
                        <i class="fas fa-award"></i>
                        <span>Beasiswa</span>
                    </a>
                </li>
            </div>
            
            <div class="menu-divider"></div>
            
            <div class="menu-group">
                <div class="menu-group-title">Administrasi</div>
                <li class="nav-item">
                    <a href="modules/laporan/index.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/pengaturan.php" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </div>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-title">
                <h1>Selamat Datang, <?php echo htmlspecialchars($nama_user); ?>!</h1>
                <p>Sistem Informasi Sekolah - Kelola data dengan mudah dan efisien.</p>
            </div>
            <div class="date-time">
                <div class="date"><?php echo $hari_ini; ?></div>
                <div class="time"><?php echo $jam_sekarang; ?> WIB</div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-icon student">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-title">Total Siswa</div>
                <div class="stat-value"><?php echo number_format($total_siswa); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-user-plus me-1"></i> <?php echo $siswa_baru_hari_ini; ?> baru hari ini
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon teacher">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-title">Total Guru</div>
                <div class="stat-value"><?php echo number_format($total_guru); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-check me-1"></i> Aktif
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon class">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-title">Total Kelas</div>
                <div class="stat-value"><?php echo number_format($total_kelas); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-check me-1"></i> Tersedia
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon user">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="stat-title">Pengguna Sistem</div>
                <div class="stat-value"><?php echo number_format($total_pengguna); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-user-shield me-1"></i> Admin
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3 class="section-title">
                <i class="fas fa-bolt me-2"></i>Aksi Cepat
            </h3>
            <div class="action-buttons">
                <a href="modules/siswa/tambah.php" class="action-btn">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah Siswa</span>
                </a>
                <a href="modules/guru/tambah.php" class="action-btn">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Tambah Guru</span>
                </a>
                <a href="modules/beasiswa/tambah.php" class="action-btn">
                    <i class="fas fa-award"></i>
                    <span>Tambah Beasiswa</span>
                </a>
                <a href="modules/mapel/tambah.php" class="action-btn">
                    <i class="fas fa-book-medical"></i>
                    <span>Tambah Mapel</span>
                </a>
                <a href="modules/kelas/tambah.php" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Kelas</span>
                </a>
                <a href="modules/laporan/index.php" class="action-btn">
                    <i class="fas fa-print"></i>
                    <span>Cetak Laporan</span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h3 class="section-title">
                        <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                    </h3>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <h5>Siswa Baru</h5>
                                <p><?php echo $siswa_baru_hari_ini; ?> siswa baru mendaftar hari ini</p>
                            </div>
                            <div class="activity-time">
                                Hari ini
                            </div>
                        </div>
                        
                        <?php if (!empty($aktifitas_terbaru)): ?>
                            <?php foreach ($aktifitas_terbaru as $siswa): ?>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h5><?php echo htmlspecialchars($siswa['nama_siswa']); ?></h5>
                                        <p>Telah terdaftar sebagai siswa baru</p>
                                    </div>
                                    <div class="activity-time">
                                        <?php echo date('d M', strtotime($siswa['tanggal_daftar'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <h5>Tidak ada aktivitas terbaru</h5>
                                    <p>Belum ada siswa yang terdaftar</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- System Information -->
                <div class="system-info">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                    </h3>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-code-branch"></i>
                        </div>
                        <div class="info-content">
                            <h5>Versi Sistem</h5>
                            <p>Sistem Informasi Sekolah v2.1</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="info-content">
                            <h5>Database</h5>
                            <p>MySQL - <?php echo number_format($total_siswa + $total_guru + $total_kelas + $total_mapel); ?> record</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="info-content">
                            <h5>Server</h5>
                            <p>Apache / PHP <?php echo phpversion(); ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="info-content">
                            <h5>Status Keamanan</h5>
                            <p class="text-success">
                                <i class="fas fa-check-circle me-1"></i> Aman
                            </p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="info-content">
                            <h5>Last Backup</h5>
                            <p><?php echo date('d M Y, H:i'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="mb-2">Sistem Informasi Sekolah &copy; <?php echo date('Y'); ?></p>
            <p class="mb-0">Admin Panel - Kelola data sekolah dengan mudah</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Auto update waktu
        function updateTime() {
            const now = new Date();
            const options = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID');
            const dateEl = document.querySelector('.date');
            const timeEl = document.querySelector('.time');
            if (dateEl) dateEl.textContent = dateStr;
            if (timeEl) timeEl.textContent = timeStr + ' WIB';
        }
        setInterval(updateTime, 1000);
    </script>
</body>
</html>