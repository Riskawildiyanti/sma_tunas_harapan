<?php
require_once '../koneksi.php';
requireLogin();

require_once 'modules/siswa.php';
$siswa = new siswa();
$siswa = $siswa->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .dataTables_wrapper {
            margin-top: 20px;
        }
        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
        }
        .status-aktif { color: #28a745; font-weight: bold; }
        .status-lulus { color: #17a2b8; font-weight: bold; }
        .status-pindah { color: #ffc107; font-weight: bold; }
        .status-dropout { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-users me-2"></i>Data Siswa
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                            <i class="fas fa-plus me-2"></i>Tambah Siswa
                        </button>
                    </div>
                </div>
                
                <!-- Filter dan Pencarian -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Cari siswa berdasarkan nama, NIS, atau NISN...">
                                    <button class="btn btn-outline-primary" type="button" id="searchButton">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterStatus">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Pindah">Pindah</option>
                                    <option value="Drop Out">Drop Out</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Data Siswa -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Daftar Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="siswaTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = $siswa->fetch_assoc()) {
                                        $statusClass = 'status-' . strtolower(str_replace(' ', '-', $row['status_siswa']));
                                        echo "
                                        <tr>
                                            <td>{$no}</td>
                                            <td>{$row['nis']}</td>
                                            <td>{$row['nama_siswa']}</td>
                                            <td>" . ($row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') . "</td>
                                            <td>" . (isset($row['nama_kelas']) ? $row['nama_kelas'] : '-') . "</td>
                                            <td><span class='{$statusClass}'>{$row['status_siswa']}</span></td>
                                            <td>" . date('d/m/Y', strtotime($row['tanggal_masuk'])) . "</td>
                                            <td>
                                                <button class='btn btn-sm btn-info btn-action' onclick='viewSiswa({$row['id_siswa']})' title='Lihat Detail'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                                <button class='btn btn-sm btn-warning btn-action' onclick='editSiswa({$row['id_siswa']})' title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                <button class='btn btn-sm btn-danger btn-action' onclick='deleteSiswa({$row['id_siswa']}, \"{$row['nama_siswa']}\")' title='Hapus'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>
                                        </tr>";
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Modal Tambah Siswa -->
    <?php include 'modals/tambah_siswa.php'; ?>
    
    <!-- Modal Edit Siswa -->
    <?php include 'modals/edit_siswa.php'; ?>
    
    <!-- Modal View Siswa -->
    <div class="modal fade" id="modalViewSiswa" tabindex="-1" aria-labelledby="modalViewSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalViewSiswaLabel">
                        <i class="fas fa-user me-2"></i>Detail Data Siswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="siswaDetailContent">
                    <!-- Konten akan diisi oleh JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk melihat detail siswa
    function viewSiswa(id) {
        fetch(`api/siswa.php?action=get&id=${id}`)
            .then(response => response.json())
            .then(data => {
                const statusClass = 'status-' + data.status_siswa.toLowerCase().replace(' ', '-');
                
                const html = `
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="fas fa-user-graduate fa-3x text-primary"></i>
                            </div>
                            <h5 class="mt-3">${data.nama_siswa}</h5>
                            <span class="badge bg-primary">${data.nis}</span>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">NISN</th>
                                    <td>${data.nisn}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>${data.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                                </tr>
                                <tr>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <td>${data.tempat_lahir}, ${new Date(data.tanggal_lahir).toLocaleDateString('id-ID')}</td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>${data.agama}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>${data.nama_kelas || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="${statusClass}">${data.status_siswa}</span></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>${data.alamat || '-'}</td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td>${data.no_telepon || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>${data.email || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Masuk</th>
                                    <td>${new Date(data.tanggal_masuk).toLocaleDateString('id-ID')}</td>
                                </tr>
                            </table>
                            
                            <h6 class="mt-4">Data Orang Tua</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nama Ayah</th>
                                    <td>${data.nama_ayah || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Nama Ibu</th>
                                    <td>${data.nama_ibu || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Pekerjaan Orang Tua</th>
                                    <td>${data.pekerjaan_ortu || '-'}</td>
                                </tr>
                                <tr>
                                    <th>Alamat Orang Tua</th>
                                    <td>${data.alamat_ortu || '-'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
                
                document.getElementById('siswaDetailContent').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('modalViewSiswa'));
                modal.show();
            });
    }
    
    // Fungsi untuk menghapus siswa
    function deleteSiswa(id, nama) {
        Swal.fire({
            title: 'Hapus Data Siswa?',
            text: `Apakah Anda yakin ingin menghapus data siswa "${nama}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`api/siswa.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message
                        });
                    }
                });
            }
        });
    }
    
    // Fungsi pencarian
    document.getElementById('searchButton').addEventListener('click', function() {
        const keyword = document.getElementById('searchInput').value;
        searchSiswa(keyword);
    });
    
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const keyword = this.value;
            searchSiswa(keyword);
        }
    });
    
    function searchSiswa(keyword) {
        fetch(`api/siswa.php?action=search&keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                updateSiswaTable(data);
            });
    }
    
    // Filter berdasarkan status
    document.getElementById('filterStatus').addEventListener('change', function() {
        const status = this.value;
        if (status) {
            fetch(`api/siswa.php?action=filter&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    updateSiswaTable(data);
                });
        } else {
            // Reset ke semua data
            location.reload();
        }
    });
    
    function updateSiswaTable(data) {
        const tbody = document.querySelector('#siswaTable tbody');
        let html = '';
        
        data.forEach((row, index) => {
            const statusClass = 'status-' + row.status_siswa.toLowerCase().replace(' ', '-');
            html += `
            <tr>
                <td>${index + 1}</td>
                <td>${row.nis}</td>
                <td>${row.nama_siswa}</td>
                <td>${row.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                <td>${row.nama_kelas || '-'}</td>
                <td><span class="${statusClass}">${row.status_siswa}</span></td>
                <td>${new Date(row.tanggal_masuk).toLocaleDateString('id-ID')}</td>
                <td>
                    <button class='btn btn-sm btn-info btn-action' onclick='viewSiswa(${row.id_siswa})' title='Lihat Detail'>
                        <i class='fas fa-eye'></i>
                    </button>
                    <button class='btn btn-sm btn-warning btn-action' onclick='editSiswa(${row.id_siswa})' title='Edit'>
                        <i class='fas fa-edit'></i>
                    </button>
                    <button class='btn btn-sm btn-danger btn-action' onclick='deleteSiswa(${row.id_siswa}, "${row.nama_siswa}")' title='Hapus'>
                        <i class='fas fa-trash'></i>
                    </button>
                </td>
            </tr>`;
        });
        
        tbody.innerHTML = html;
    }
    </script>
</body>
</html>