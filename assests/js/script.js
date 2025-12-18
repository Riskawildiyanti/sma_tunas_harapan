// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.createElement('button');
    sidebarToggle.className = 'btn btn-primary d-md-none position-fixed';
    sidebarToggle.style = 'bottom: 20px; right: 20px; z-index: 1000;';
    sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
    sidebarToggle.onclick = function() {
        document.querySelector('.sidebar').classList.toggle('show');
    };
    document.body.appendChild(sidebarToggle);
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.btn-primary.position-fixed');
            if (!sidebar.contains(event.target) && event.target !== toggleBtn && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
});