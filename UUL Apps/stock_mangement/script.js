// Stock Management Dashboard JavaScript

class StockManager {
    constructor() {
        this.initializeElements();
        this.attachEventListeners();
        this.loadProducts();
        this.setDefaultDate();
    }

    initializeElements() {
        // Forms
        this.addForm = document.getElementById('addProductForm');
        this.updateForm = document.getElementById('updateForm');
        this.addProductModal = document.getElementById('addProductModal');
        this.showAddFormBtn = document.getElementById('showAddFormBtn');
        this.closeAddModal = document.querySelector('.close-add');

        // Filter elements
        this.searchInput = document.getElementById('searchInput');
        this.categoryFilter = document.getElementById('categoryFilter');
        this.statusFilter = document.getElementById('statusFilter');
        this.refreshBtn = document.getElementById('refreshBtn');

        // Table elements
        this.tableBody = document.getElementById('stockTableBody');

        // Modal elements
        this.modal = document.getElementById('updateModal');
        this.closeModal = document.querySelector('.close');

        // Statistics elements
        this.totalProducts = document.getElementById('totalProducts');
        this.totalQuantity = document.getElementById('totalQuantity');
        this.expiredProducts = document.getElementById('expiredProducts');
        this.expiringSoon = document.getElementById('expiringSoon');
        this.slowMoving = document.getElementById('slowMoving');

        // Other elements
        this.loading = document.getElementById('loading');
        this.message = document.getElementById('message');
    }

    attachEventListeners() {
        // Form submissions
        this.addForm.addEventListener('submit', (e) => this.handleAddProduct(e));
        this.updateForm.addEventListener('submit', (e) => this.handleUpdateProduct(e));

        // Show/hide add modal
        this.showAddFormBtn.addEventListener('click', () => this.openAddModal());
        this.closeAddModal.addEventListener('click', () => this.closeAddProductModal());

        // Filter events
        this.searchInput.addEventListener('input', () => this.filterProducts());
        this.categoryFilter.addEventListener('change', () => this.filterProducts());
        this.statusFilter.addEventListener('change', () => this.filterProducts());
        this.refreshBtn.addEventListener('click', () => this.loadProducts());

        // Modal events
        this.closeModal.addEventListener('click', () => this.closeUpdateModal());
        window.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeUpdateModal();
            }
            if (e.target === this.addProductModal) {
                this.closeAddProductModal();
            }
        });
    }

    openAddModal() {
        this.addProductModal.style.display = 'block';
        this.setDefaultDate();
    }

    closeAddProductModal() {
        this.addProductModal.style.display = 'none';
        this.addForm.reset();
    }

    setDefaultDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('offloadDate').value = today;
    }

    async handleAddProduct(e) {
        e.preventDefault();

        const formData = new FormData(this.addForm);

        try {
            this.showLoading();
            const response = await fetch('database.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showMessage('Product added successfully!', 'success');
                this.closeAddProductModal(); // Close modal on success
                this.loadProducts();
            } else {
                this.showMessage(result.error || 'Failed to add product', 'error');
            }
        } catch (error) {
            this.showMessage('Network error occurred', 'error');
            console.error('Error:', error);
        } finally {
            this.hideLoading();
        }
    }
    async handleUpdateProduct(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('id', document.getElementById('updateProductId').value);
        formData.append('current_quantity', document.getElementById('currentQuantity').value);
        formData.append('update_notes', document.getElementById('updateNotes').value);

        try {
            this.showLoading();
            const response = await fetch('database.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showMessage('Product updated successfully!', 'success');
                this.closeUpdateModal();
                this.loadProducts();
            } else {
                this.showMessage(result.error || 'Failed to update product', 'error');
            }
        } catch (error) {
            this.showMessage('Network error occurred', 'error');
            console.error('Error:', error);
        } finally {
            this.hideLoading();
        }
    }

    async loadProducts() {
        try {
            this.showLoading();
            const response = await fetch('database.php?action=get_all');
            const result = await response.json();

            if (result.success) {
                this.products = result.data;
                this.displayProducts(this.products);
            } else {
                this.showMessage(result.error || 'Failed to load products', 'error');
            }
        } catch (error) {
            this.showMessage('Failed to load products', 'error');
            console.error('Error:', error);
        } finally {
            this.hideLoading();
        }
    }

    displayProducts(products) {
        if (!products || products.length === 0) {
            this.tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px;">No products found</td></tr>';
            return;
        }

        this.tableBody.innerHTML = products.map(product => {
            const remainingDays = product.expiry_date && product.expiry_date !== '0000-00-00'
                ? this.calculateRemainingDays(product.expiry_date)
                : null;
            const status = this.getProductStatusByExpiry(product.expiry_date);

            return `
            <tr>
                <td>${product.product_category}</td>
                <td>${product.product_name}</td>
                <td>${product.consignment_track}</td>
                <td>${this.formatDate(product.offload_date)}</td>
                <td>${product.current_quantity}</td>
                <td><span class="status-badge ${status.class}">${status.text}${remainingDays !== null ? ` (${remainingDays} days)` : ''}</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-update" onclick="stockManager.openUpdateModal(${product.id}, '${product.product_name}', ${product.current_quantity})">Update</button>
                        <button class="btn btn-delete" onclick="stockManager.deleteProduct(${product.id})">Delete</button>
                    </div>
                </td>
            </tr>
        `;
        }).join('');
    }

    calculateDaysInStock(offloadDate) {
        const today = new Date();
        const offload = new Date(offloadDate);
        const timeDiff = today.getTime() - offload.getTime();
        return Math.floor(timeDiff / (1000 * 3600 * 24));
    }

    getProductStatus(product, daysInStock) {
        // Check if expired (assuming 1 year shelf life from manufacture date)
        const manufactureDate = new Date(product.manufacture_date);
        const expiryDate = new Date(manufactureDate);
        expiryDate.setFullYear(expiryDate.getFullYear() + 1);
        const today = new Date();

        if (today > expiryDate) {
            return { class: 'status-expired', text: 'Expired' };
        }

        // Check if expiring soon (within 30 days)
        const daysUntilExpiry = Math.floor((expiryDate - today) / (1000 * 3600 * 24));
        if (daysUntilExpiry <= 30 && daysUntilExpiry > 0) {
            return { class: 'status-expiring', text: 'Expiring Soon' };
        }

        // Check if slow moving (90+ days in stock)
        if (daysInStock >= 90) {
            return { class: 'status-slow', text: 'Slow Moving' };
        }

        return { class: 'status-normal', text: 'Normal' };
    }

    calculateRemainingDays(expiryDate) {
        const today = new Date();
        const expiry = new Date(expiryDate);
        const timeDiff = expiry.getTime() - today.getTime();
        return Math.floor(timeDiff / (1000 * 3600 * 24));
    }

    getProductStatusByExpiry(expiryDate) {
        if (!expiryDate || expiryDate === '0000-00-00' || expiryDate === null) {
            return { class: 'status-na', text: 'N/A' };
        }

        const today = new Date();
        const expiry = new Date(expiryDate);
        const timeDiff = expiry.getTime() - today.getTime();
        const remainingDays = Math.floor(timeDiff / (1000 * 3600 * 24));

        if (remainingDays < 0) {
            return { class: 'status-expired', text: 'Expired' };
        }
        if (remainingDays <= 30) {
            return { class: 'status-expiring', text: 'Expiring Soon' };
        }
        if (remainingDays <= 90) {
            return { class: 'status-slow', text: 'Expires Soon' };
        }
        return { class: 'status-normal', text: 'Good' };
    }

    filterProducts() {
        if (!this.products) return;

        const searchTerm = this.searchInput.value.toLowerCase();
        const categoryFilter = this.categoryFilter.value;
        const statusFilter = this.statusFilter.value;

        const filteredProducts = this.products.filter(product => {
            const matchesSearch = !searchTerm ||
                product.product_name.toLowerCase().includes(searchTerm) ||
                product.product_category.toLowerCase().includes(searchTerm) ||
                product.consignment_track.toLowerCase().includes(searchTerm);

            const matchesCategory = !categoryFilter || product.product_category === categoryFilter;

            let matchesStatus = true;
            if (statusFilter) {
                const daysInStock = this.calculateDaysInStock(product.offload_date);
                const status = this.getProductStatus(product, daysInStock);

                switch (statusFilter) {
                    case 'expired':
                        matchesStatus = status.text === 'Expired';
                        break;
                    case 'expiring_soon':
                        matchesStatus = status.text === 'Expiring Soon';
                        break;
                    case 'slow_moving':
                        matchesStatus = status.text === 'Slow Moving';
                        break;
                }
            }

            return matchesSearch && matchesCategory && matchesStatus;
        });

        this.displayProducts(filteredProducts);
    }

    openUpdateModal(id, productName, currentQuantity) {
        document.getElementById('updateProductId').value = id;
        document.getElementById('updateProductName').textContent = productName;
        document.getElementById('currentQuantity').value = currentQuantity;
        document.getElementById('updateNotes').value = '';
        this.modal.style.display = 'block';
    }

    closeUpdateModal() {
        this.modal.style.display = 'none';
    }

    async deleteProduct(id) {
        if (!confirm('Are you sure you want to delete this product?')) {
            return;
        }

        try {
            this.showLoading();
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            const response = await fetch('database.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showMessage('Product deleted successfully!', 'success');
                this.loadProducts();
            } else {
                this.showMessage(result.error || 'Failed to delete product', 'error');
            }
        } catch (error) {
            this.showMessage('Network error occurred', 'error');
            console.error('Error:', error);
        } finally {
            this.hideLoading();
        }
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString();
    }

    formatDateTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toLocaleString();
    }

    showLoading() {
        this.loading.style.display = 'block';
    }

    hideLoading() {
        this.loading.style.display = 'none';
    }

    showMessage(text, type = 'info') {
        this.message.textContent = text;
        this.message.className = `message ${type}`;
        this.message.style.display = 'block';

        // Auto hide after 5 seconds
        setTimeout(() => {
            this.message.style.display = 'none';
        }, 5000);
    }
}



// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.stockManager = new StockManager();
});

