// public/assets/js/seller/pricing-ui.js

(function() {
    'use strict';

    const PricingManager = {
        modal: null,
        form: null,
        currentButton: null,

        init: function() {
            this.modal = document.getElementById('pricing-modal');
            this.form = document.getElementById('pricing-form');
            
            if (!this.modal || !this.form) {
                console.warn('Pricing modal elements not found, will create them');
                this.createModal();
            }

            this.attachEventListeners();
            console.log('Pricing Manager initialized');
        },

        createModal: function() {
            // Create modal HTML
            const modalHTML = `
                <div id="pricing-modal" class="pricing-modal-overlay">
                    <div class="pricing-modal">
                        <div class="pricing-modal-header">
                            <h3>Update Price</h3>
                            <button type="button" class="pricing-modal-close" aria-label="Close">&times;</button>
                        </div>
                        <form id="pricing-form" class="pricing-modal-form">
                            <div class="pricing-form-group">
                                <label for="product-name-display">Product</label>
                                <div id="product-name-display" class="pricing-product-display"></div>
                            </div>
                            
                            <div class="pricing-form-row">
                                <div class="pricing-form-group">
                                    <label for="current-price-display">Current Price</label>
                                    <div id="current-price-display" class="pricing-price-display"></div>
                                </div>
                                <div class="pricing-form-group">
                                    <label for="base-cost-display">Base Cost</label>
                                    <div id="base-cost-display" class="pricing-cost-display"></div>
                                </div>
                            </div>
                            
                            <div class="pricing-form-group">
                                <label for="new-price">New Price</label>
                                <div class="pricing-input-group">
                                    <span class="pricing-currency-symbol">₦</span>
                                    <input type="number" id="new-price" name="new_price" step="0.01" min="0" required placeholder="Enter new price">
                                </div>
                                <div id="price-warning" class="pricing-warning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    New price is below base cost
                                </div>
                            </div>

                            <div class="pricing-form-actions">
                                <button type="button" class="pricing-btn-cancel">Cancel</button>
                                <button type="submit" class="pricing-btn-update">
                                    <i class="fas fa-check"></i> Update Price
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
            this.modal = document.getElementById('pricing-modal');
            this.form = document.getElementById('pricing-form');
        },

        attachEventListeners: function() {
            // Handle form submission
            if (this.form) {
                this.form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handlePriceUpdate();
                });
            }

            // Handle update price buttons
            const updateButtons = document.querySelectorAll('.update-price');
            updateButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.openModal(button);
                });
            });

            // Close modal on background click
            if (this.modal) {
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal) {
                        this.closeModal();
                    }
                });

                // Close modal on Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                        this.closeModal();
                    }
                });
            }

            // Close button
            const closeBtn = document.querySelector('.pricing-modal-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.closeModal());
            }

            // Cancel button
            const cancelBtn = document.querySelector('.pricing-btn-cancel');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => this.closeModal());
            }

            // New price input validation
            const newPriceInput = document.getElementById('new-price');
            if (newPriceInput) {
                newPriceInput.addEventListener('input', () => {
                    this.updatePriceWarning();
                });
            }
        },

        openModal: function(button) {
            this.currentButton = button;
            const productId = button.dataset.productId;
            const productName = button.dataset.productName;
            const currentPrice = parseFloat(button.dataset.currentPrice) || 0;

            // Get base cost from the table row
            const row = button.closest('tr');
            const baseCost = parseFloat(
                row.querySelector('td:nth-child(4)').textContent.replace('₦', '').replace(/,/g, '').trim()
            ) || 0;

            // Store data for submission
            this.currentProductId = productId;
            this.currentCost = baseCost;

            // Populate modal
            document.getElementById('product-name-display').textContent = productName;
            document.getElementById('current-price-display').textContent = '₦' + currentPrice.toFixed(2);
            document.getElementById('base-cost-display').textContent = '₦' + baseCost.toFixed(2);
            
            const newPriceInput = document.getElementById('new-price');
            newPriceInput.value = currentPrice.toFixed(2);

            // Show modal
            this.modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Focus on input
            setTimeout(() => {
                newPriceInput.focus();
                newPriceInput.select();
            }, 100);
        },

        closeModal: function() {
            this.modal.classList.remove('active');
            document.body.style.overflow = '';
            this.form.reset();
            this.currentButton = null;
            const warning = document.getElementById('price-warning');
            if (warning) warning.style.display = 'none';
        },

        updatePriceWarning: function() {
            const newPrice = parseFloat(document.getElementById('new-price').value) || 0;
            const warning = document.getElementById('price-warning');
            
            if (newPrice < this.currentCost && newPrice > 0) {
                warning.style.display = 'flex';
            } else {
                warning.style.display = 'none';
            }
        },

        handlePriceUpdate: async function() {
            const newPrice = parseFloat(document.getElementById('new-price').value) || 0;
            
            if (isNaN(newPrice) || newPrice < 0) {
                this.showError('Please enter a valid price');
                return;
            }

            if (newPrice < this.currentCost) {
                if (!confirm('Warning: The new price is below the product cost. Do you want to continue?')) {
                    return;
                }
            }

            try {
                const response = await fetch('/dynamic/dynamic_pricing/public/seller/pricing/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: this.currentProductId,
                        price: newPrice
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update the table
                    const row = this.currentButton.closest('tr');
                    row.querySelector('td:nth-child(3)').textContent = '₦' + newPrice.toFixed(2);
                    
                    // Update margin
                    const margin = this.currentCost > 0 ? ((newPrice - this.currentCost) / this.currentCost) * 100 : 0;
                    row.querySelector('td:nth-child(5)').textContent = margin.toFixed(1) + '%';
                    
                    // Close modal
                    this.closeModal();
                    this.showSuccess('Price updated successfully');
                } else {
                    this.showError(result.message || 'Failed to update price');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showError('An error occurred while updating the price');
            }
        },

        showSuccess: function(message) {
            const alert = document.createElement('div');
            alert.className = 'pricing-alert pricing-alert-success';
            alert.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        },

        showError: function(message) {
            const alert = document.createElement('div');
            alert.className = 'pricing-alert pricing-alert-error';
            alert.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            PricingManager.init();
        });
    } else {
        PricingManager.init();
    }

    // Make globally available
    window.PricingManager = PricingManager;
})();