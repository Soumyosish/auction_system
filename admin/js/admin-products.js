document.addEventListener('DOMContentLoaded', function() {
    // Load products list
    loadProducts();
    
    // Add product button click event
    document.getElementById('add-product-btn').addEventListener('click', function() {
        openProductModal();
    });
    
    // Close modal when clicking on X
    document.querySelector('.close').addEventListener('click', function() {
        closeProductModal();
    });
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('product-modal')) {
            closeProductModal();
        }
    });
    
    // Product form submit event
    document.getElementById('product-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveProduct();
    });
    
    // Product image preview
    document.getElementById('product-image').addEventListener('change', function() {
        previewImage(this);
    });
    
    // Search products
    document.getElementById('product-search').addEventListener('input', function() {
        loadProducts();
    });
    
    // Filter by category
    document.getElementById('category-filter').addEventListener('change', function() {
        loadProducts();
    });
});

// Load products list
function loadProducts() {
    const searchTerm = document.getElementById('product-search').value;
    const categoryFilter = document.getElementById('category-filter').value;
    
    fetch(`../api/admin/get_products.php?search=${searchTerm}&category=${categoryFilter}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderProductsList(data.data);
            } else {
                showError(data.error);
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
            showError('Failed to load products');
        });
}

// Render products list
function renderProductsList(products) {
    const tableBody = document.getElementById('products-list');
    
    if (products.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No products found</td></tr>';
        return;
    }
    
    let html = '';
    products.forEach(product => {
        html += `
            <tr>
                <td>${product.id}</td>
                <td>
                    <img src="../uploads/${product.image}" alt="${product.name}" width="50" height="50" style="object-fit: cover; border-radius: 4px;">
                </td>
                <td>${product.name}</td>
                <td>${product.category.charAt(0).toUpperCase() + product.category.slice(1)}</td>
                <td>${truncateText(product.description, 50)}</td>
                <td>${product.created_at}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit-btn" onclick="editProduct(${product.id})">Edit</button>
                        <button class="action-btn delete-btn" onclick="deleteProduct(${product.id})">Delete</button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

// Open product modal
function openProductModal(productId = null) {
    // Reset form
    document.getElementById('product-form').reset();
    document.getElementById('image-preview').innerHTML = '';
    
    if (productId) {
        // Edit mode
        document.getElementById('modal-title').textContent = 'Edit Product';
        fetchProductDetails(productId);
    } else {
        // Add mode
        document.getElementById('modal-title').textContent = 'Add New Product';
        document.getElementById('product-id').value = '';
    }
    
    document.getElementById('product-modal').style.display = 'block';
}

// Close product modal
function closeProductModal() {
    document.getElementById('product-modal').style.display = 'none';
}

// Fetch product details for editing
function fetchProductDetails(productId) {
    fetch(`../api/admin/get_product.php?id=${productId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const product = data.data;
                
                // Fill form with product details
                document.getElementById('product-id').value = product.id;
                document.getElementById('product-name').value = product.name;
                document.getElementById('product-category').value = product.category;
                document.getElementById('product-description').value = product.description;
                
                // Show image preview
                if (product.image) {
                    const imgPreview = document.getElementById('image-preview');
                    imgPreview.innerHTML = `<img src="../uploads/${product.image}" alt="${product.name}">`;
                }
            } else {
                showError(data.error);
                closeProductModal();
            }
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
            showError('Failed to load product details');
            closeProductModal();
        });
}

// Save product (add or update)
function saveProduct() {
    const form = document.getElementById('product-form');
    const formData = new FormData(form);
    
    const productId = document.getElementById('product-id').value;
    const url = productId ? '../api/admin/update_product.php' : '../api/admin/add_product.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeProductModal();
            loadProducts();
        } else {
            showError(data.error);
        }
    })
    .catch(error => {
        console.error('Error saving product:', error);
        showError('Failed to save product');
    });
}

// Delete product
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch('../api/admin/delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Product deleted successfully');
                loadProducts();
            } else {
                showError(data.error);
            }
        })
        .catch(error => {
            console.error('Error deleting product:', error);
            showError('Failed to delete product');
        });
    }
}

// Edit product
function editProduct(productId) {
    openProductModal(productId);
}

// Preview image before upload
function previewImage(input) {
    const imgPreview = document.getElementById('image-preview');
    imgPreview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            imgPreview.appendChild(img);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Truncate text with ellipsis
function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
}

// Show error message
function showError(message) {
    alert(message);
}