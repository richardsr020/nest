<?php
// nest/app/views/partials/admin_footer.php
?>
    <script>
    document.getElementById('logout-btn')?.addEventListener('click', async function () {
        if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
            try {
                const response = await fetch('/nest/app/api/auth/logout.php');
                const data = await response.json();
                if (data.success) {
                    window.location.href = '/nest/?page=home';
                }
            } catch (e) { console.error(e); }
        }
    });

    // Toasts simples
    function showToast(message, type) {
        let toast = document.getElementById('adminToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'adminToast';
            toast.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;max-width:380px;padding:16px 20px;border-radius:12px;font-weight:600;font-size:0.92rem;color:#fff;box-shadow:0 12px 40px rgba(0,0,0,.2);opacity:0;transform:translateY(20px);transition:all .4s ease;';
            document.body.appendChild(toast);
        }
        toast.style.background = type === 'error' ? 'linear-gradient(135deg,#ff4757,#e63946)' : 'linear-gradient(135deg,#00d664,#00b354)';
        toast.textContent = message;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        clearTimeout(toast._t);
        toast._t = setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(20px)'; }, 4000);
    }
    </script>
</div>
</body>
</html>
