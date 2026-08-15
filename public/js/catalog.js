// nest/public/js/catalog.js
// Actions catalogue : commander / télécharger

document.addEventListener('DOMContentLoaded', function () {

    const showToast = (message, type) => {
        let toast = document.getElementById('nestToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'nestToast';
            toast.style.cssText = `
                position: fixed; bottom: 24px; right: 24px; z-index: 9999;
                max-width: 380px; padding: 16px 20px; border-radius: 12px;
                font-weight: 600; font-size: 0.92rem; color: #fff;
                box-shadow: 0 12px 40px rgba(0,0,0,0.2);
                display: flex; align-items: center; gap: 12px;
                transition: all 0.4s ease; opacity: 0; transform: translateY(20px);
            `;
            document.body.appendChild(toast);
        }
        toast.style.background = type === 'success' ? 'linear-gradient(135deg,#00d664,#00b354)' : 'linear-gradient(135deg,#ff4757,#e63946)';
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        clearTimeout(toast._timer);
        toast._timer = setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
        }, 4500);
    };

    const handleOrder = async (btn) => {
        const productId = btn.getAttribute('data-product-id');
        const productName = btn.getAttribute('data-product-name') || 'Produit';
        const pricingType = btn.getAttribute('data-pricing') || 'one_time';

        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';

        try {
            const response = await fetch('/nest/app/api/products/order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: parseInt(productId) })
            });
            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                if (data.download_url) {
                    // Simuler un téléchargement via une nouvelle fenêtre
                    window.open(data.download_url, '_blank');
                }
                // Compteur local si présent
                const counter = btn.closest('[data-download-count]');
                if (counter) {
                    counter.textContent = parseInt(counter.textContent || 0) + 1;
                }
            } else {
                showToast(data.message || 'Erreur', 'error');
            }
        } catch (e) {
            console.error('Order error:', e);
            showToast('Erreur de connexion au serveur', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = original;
        }
    };

    document.querySelectorAll('[data-order-btn]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            handleOrder(btn);
        });
    });
});
