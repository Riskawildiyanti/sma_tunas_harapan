<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-home me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'siswa.php' ? 'active' : ''; ?>" href="siswa.php">
                    <i class="fas fa-users me-2"></i>
                    Data Siswa
                </a>
            </li>
            
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'guru.php' ? 'active' : ''; ?>" href="guru.php">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Data Guru
                </a>
            </li>
            
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelas.php' ? 'active' : ''; ?>" href="kelas.php">
                    <i class="fas fa-door-open me-2"></i>
                    Data Kelas
                </a>
            </li>
            
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'mapel.php' ? 'active' : ''; ?>" href="mapel.php">
                    <i class="fas fa-book me-2"></i>
                    Mata Pelajaran
                </a>
            </li>
            
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'beasiswa.php' ? 'active' : ''; ?>" href="beasiswa.php">
                    <i class="fas fa-award me-2"></i>
                    Beasiswa
                </a>
            </li>
            
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'nilai.php' ? 'active' : ''; ?>" href="nilai.php">
                    <i class="fas fa-chart-bar me-2"></i>
                    Nilai Siswa
                </a>
            </li>
            
            <?php if ($_SESSION['level'] == 'Admin'): ?>
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pengguna.php' ? 'active' : ''; ?>" href="pengguna.php">
                    <i class="fas fa-user-cog me-2"></i>
                    Pengguna
                </a>
            </li>
            <?php endif; ?>
            
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>" href="laporan.php">
                    <i class="fas fa-file-pdf me-2"></i>
                    Laporan
                </a>
            </li>
            
            <li class="nav-item mb-2">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
        
        <hr>
        
        <div class="p-3 bg-info bg-opacity-10 rounded">
            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Info Sistem</h6>
            <small class="text-muted">
                <div>Versi: 1.0.0</div>
                <div>Pengguna: <?php echo $_SESSION['level']; ?></div>
                <div>Login: <?php echo date('d/m/Y H:i'); ?></div>
            </small>
        </div>
    </div>
</nav>