// Global variables
let currentProductId = null;
let searchTimeout = null;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Add product form
    document.getElementById('addProductForm').addEventListener('submit', handleAddProduct);

    // Product search
    document.getElementById('productSearch').addEventListener('input', handleProductSearch);

    // Add category form
    document.getElementById('addCategoryForm').addEventListener('submit', handleAddCategory);

    // Click outside search results to close
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#productSearch') && !e.target.closest('#searchResults')) {
            document.getElementById('searchResults').classList.add('hidden');
        }
    });
}

// Load categories
async function loadCategories() {
    try {
        const response = await fetch('margin_api.php?action=get_categories');
        const data = await response.json();

        if (data.success) {
            const selects = ['productCategory', 'categoryFilter', 'viewCategoryFilter'];
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    // Keep first option (placeholder or "All Categories")
                    const firstOption = select.options[0];
                    select.innerHTML = '';
                    select.appendChild(firstOption);

                    data.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.category_name;
                        option.textContent = category.category_name;
                        select.appendChild(option);
                    });
                }
            });

            displayCategoriesList(data.categories);
        }
    } catch (error) {
        showToast('Error loading categories', 'error');
        console.error(error);
    }
}

// Display categories list
function displayCategoriesList(categories) {
    const container = document.getElementById('categoriesList');
    if (!container) return;

    container.innerHTML = '';

    categories.forEach(category => {
        const item = document.createElement('div');
        item.className = 'category-item';
        item.innerHTML = `
            <div class="category-name">${category.category_name}</div>
            <div class="category-description">${category.description || 'No description'}</div>
        `;
        container.appendChild(item);
    });
}

// Handle add product
async function handleAddProduct(e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append('action', 'add_product');
    formData.append('product_name', document.getElementById('productName').value);
    formData.append('category', document.getElementById('productCategory').value);
    formData.append('packing_quantity', document.getElementById('packingQuantity').value);
    formData.append('cost_price', document.getElementById('costPrice').value);
    formData.append('selling_price', document.getElementById('sellingPrice').value);
    formData.append('stock_available', document.getElementById('stockAvailable').value);

    try {
        const response = await fetch('margin_api.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showToast('Product added successfully', 'success');
            document.getElementById('addProductForm').reset();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Error adding product', 'error');
        console.error(error);
    }
}

// Handle product search
function handleProductSearch(e) {
    clearTimeout(searchTimeout);

    const searchTerm = e.target.value.trim();
    const category = document.getElementById('categoryFilter').value;

    if (searchTerm.length < 2) {
        document.getElementById('searchResults').classList.add('hidden');
        return;
    }

    searchTimeout = setTimeout(async () => {
        try {
            const url = `margin_api.php?action=search_products&search=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(category)}`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.success) {
                displaySearchResults(data.products);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }, 300);
}

// Display search results
function displaySearchResults(products) {
    const resultsContainer = document.getElementById('searchResults');

    if (products.length === 0) {
        resultsContainer.innerHTML = '<div class="search-result-item">No products found</div>';
        resultsContainer.classList.remove('hidden');
        return;
    }

    resultsContainer.innerHTML = '';

    products.forEach(product => {
        const item = document.createElement('div');
        item.className = 'search-result-item';
        item.innerHTML = `
            <div class="search-result-name">${product.product_name}</div>
            <div class="search-result-info">
                ${product.category} | ${product.packing_quantity || 'N/A'} | 
                UGX ${parseFloat(product.selling_price).toLocaleString()}
            </div>
        `;
        item.onclick = () => selectProduct(product.id);
        resultsContainer.appendChild(item);
    });

    resultsContainer.classList.remove('hidden');
}

// Select product and load details
async function selectProduct(productId) {
    try {
        const response = await fetch(`margin_api.php?action=get_product_details&product_id=${productId}`);
        const data = await response.json();

        if (data.success) {
            displayProductDetails(data.product);
            document.getElementById('searchResults').classList.add('hidden');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Error loading product details', 'error');
        console.error(error);
    }
}

// Display product details
function displayProductDetails(product) {
    currentProductId = product.id;

    const detailsCard = document.getElementById('productDetailsCard');
    
    const lastModified = new Date(product.price_last_modified).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    detailsCard.innerHTML = `
        <div class="product-detail-header">
            <h3 class="product-detail-title">${product.product_name}</h3>
            <button class="btn btn-primary" onclick="openEditPriceModal()">Edit Price</button>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Category</div>
                <div class="detail-value">${product.category}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Packing Quantity</div>
                <div class="detail-value">${product.packing_quantity || 'N/A'}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Selling Price</div>
                <div class="detail-value">UGX ${parseFloat(product.selling_price).toLocaleString()}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Stock Available</div>
                <div class="detail-value">${product.stock_available.toLocaleString()}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Last Price Update</div>
                <div class="detail-value" style="font-size: 0.9rem;">${lastModified}</div>
            </div>
        </div>

        <div class="margin-display">
            <div class="margin-label">Current Margin</div>
            <div class="margin-value" id="currentMargin">${product.margin}%</div>
        </div>
    `;

    detailsCard.classList.remove('hidden');
}

// Open edit price modal
function openEditPriceModal() {
    if (!currentProductId) return;

    const productName = document.querySelector('.product-detail-title').textContent;
    const currentPrice = document.querySelector('.detail-grid .detail-item:nth-child(3) .detail-value')
        .textContent.replace('UGX ', '').replace(/,/g, '');

    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('newSellingPrice').value = currentPrice;
    document.getElementById('pricePasscode').value = '';
    document.getElementById('editPriceModal').classList.add('active');
}

// Close modal
function closeModal() {
    document.getElementById('editPriceModal').classList.remove('active');
}

// Update price
async function updatePrice() {
    const newPrice = document.getElementById('newSellingPrice').value;
    const passcode = document.getElementById('pricePasscode').value;

    if (!newPrice || !passcode) {
        showToast('Please fill all fields', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'update_selling_price');
    formData.append('product_id', currentProductId);
    formData.append('selling_price', newPrice);
    formData.append('passcode', passcode);

    try {
        const response = await fetch('margin_api.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showToast('Price updated successfully', 'success');
            closeModal();
            // Reload product details
            selectProduct(currentProductId);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Error updating price', 'error');
        console.error(error);
    }
}

// Load all products
async function loadAllProducts() {
    const category = document.getElementById('viewCategoryFilter').value;
    
    try {
        const url = `margin_api.php?action=get_all_products&category=${encodeURIComponent(category)}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            displayAllProducts(data.products);
        }
    } catch (error) {
        showToast('Error loading products', 'error');
        console.error(error);
    }
}

// Display all products in table
function displayAllProducts(products) {
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';

    if (products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px;">No products found</td></tr>';
        return;
    }

    products.forEach(product => {
        const row = document.createElement('tr');
        
        const lastModified = new Date(product.price_last_modified).toLocaleDateString('en-GB', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });

        row.innerHTML = `
            <td>${product.product_name}</td>
            <td>${product.category}</td>
            <td>${product.packing_quantity || 'N/A'}</td>
            <td>UGX ${parseFloat(product.selling_price).toLocaleString()}</td>
            <td>${product.stock_available.toLocaleString()}</td>
            <td>${lastModified}</td>
            <td>
                <button class="btn btn-primary" style="padding: 5px 15px; font-size: 0.9rem;" 
                        onclick="viewProductDetails(${product.id})">View</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// View product details from table
function viewProductDetails(productId) {
    showCalculateMargin();
    setTimeout(() => {
        selectProduct(productId);
    }, 100);
}

// Handle add category
async function handleAddCategory(e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append('action', 'add_category');
    formData.append('category_name', document.getElementById('categoryName').value);
    formData.append('description', document.getElementById('categoryDescription').value);

    try {
        const response = await fetch('margin_api.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showToast('Category added successfully', 'success');
            document.getElementById('addCategoryForm').reset();
            loadCategories();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Error adding category', 'error');
        console.error(error);
    }
}

// Load products when category changes
function loadCategoryProducts() {
    const searchInput = document.getElementById('productSearch');
    if (searchInput.value.trim().length >= 2) {
        handleProductSearch({ target: searchInput });
    }
}

// Section navigation functions
function showAddProduct() {
    hideAllSections();
    document.getElementById('addProductSection').classList.remove('hidden');
}

function showCalculateMargin() {
    hideAllSections();
    document.getElementById('calculateMarginSection').classList.remove('hidden');
}

function showAllProducts() {
    hideAllSections();
    document.getElementById('allProductsSection').classList.remove('hidden');
    loadAllProducts();
}

function showManageCategories() {
    hideAllSections();
    document.getElementById('manageCategoriesSection').classList.remove('hidden');
}

function hideAllSections() {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.add('hidden');
    });
}

function closeSection(sectionId) {
    document.getElementById(sectionId).classList.add('hidden');
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}