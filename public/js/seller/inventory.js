// Inventory Management JS

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.update-stock').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const productId = btn.getAttribute('data-product-id');
      const newStock = prompt('Enter new stock quantity:');
      if (newStock !== null && !isNaN(newStock)) {
        // Get the base URL from window or construct it
        const baseUrl = window.location.pathname.split('/seller')[0] || '/dynamic/dynamic_pricing/public';
        const url = baseUrl + '/seller/inventory/update';
        
        fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ product_id: productId, stock_quantity: newStock })
        })
        .then(res => {
          if (!res.ok) {
            throw new Error('HTTP error, status = ' + res.status);
          }
          return res.json();
        })
        .then(data => {
          alert(data.success ? 'Stock updated!' : 'Error: ' + (data.error || 'Unknown error'));
          if (data.success) location.reload();
        })
        .catch(error => {
          console.error('Fetch error:', error);
          alert('Failed to update stock: ' + error.message);
        });
      }
    });
  });
});

