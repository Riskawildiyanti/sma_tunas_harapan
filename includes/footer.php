            </div> <!-- End row -->
        </div> <!-- End container-fluid -->
    </div> <!-- End main-content -->

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">
                        <i class="fas fa-school me-1"></i> SMA TUNAS HARAPAN
                    </span>
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">
                        Sistem Informasi Data Siswa &copy; <?php echo date('Y'); ?>
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom JS -->
    <script src="../assets/js/script.js"></script>
    
    <script>
        // Inisialisasi DataTables
        $(document).ready(function() {
            $('.data-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                },
                "pageLength": 10,
                "responsive": true
            });
        });
        
        // Konfirmasi sebelum hapus
        function confirmDelete(url) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                window.location.href = url;
            }
            return false;
        }
        
        // Toggle sidebar di mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }
    </script>
</body>
</html>