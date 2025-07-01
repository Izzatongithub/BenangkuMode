// Product data
// const products = [
//     {
//         id: 1,
//         name: "Scarf Merajut Tradisional",
//         category: "scarf",
//         price: 150000,
//         description: "Scarf hangat dengan motif tradisional Lombok yang elegan",
//         image: "fas fa-scarf",
//         color: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)"
//     },
//     {
//         id: 2,
//         name: "Cardigan Handmade",
//         category: "cardigan",
//         price: 350000,
//         description: "Cardigan nyaman dengan detail merajut yang indah dan trendy",
//         image: "fas fa-tshirt",
//         color: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
//     },
//     {
//         id: 3,
//         name: "Tas Merajut Unik",
//         category: "bag",
//         price: 200000,
//         description: "Tas unik dengan teknik merajut yang kuat dan fashionable",
//         image: "fas fa-bag-shopping",
//         color: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
//     },
//     {
//         id: 4,
//         name: "Topi Merajut",
//         category: "accessories",
//         price: 120000,
//         description: "Topi merajut yang hangat dan stylish untuk cuaca dingin",
//         image: "fas fa-hat-cowboy",
//         color: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)"
//     },
//     {
//         id: 5,
//         name: "Sarung Tangan Merajut",
//         category: "accessories",
//         price: 80000,
//         description: "Sarung tangan merajut yang nyaman dan hangat",
//         image: "fas fa-hand-paper",
//         color: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)"
//     },
//     {
//         id: 6,
//         name: "Syal Merajut Premium",
//         category: "scarf",
//         price: 180000,
//         description: "Syal premium dengan bahan wol berkualitas tinggi",
//         image: "fas fa-scarf",
//         color: "linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)"
//     },
//     {
//         id: 7,
//         name: "Vest Merajut",
//         category: "cardigan",
//         price: 280000,
//         description: "Vest merajut yang cocok untuk layering outfit",
//         image: "fas fa-tshirt",
//         color: "linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)"
//     },
//     {
//         id: 8,
//         name: "Tas Ransel Merajut",
//         category: "bag",
//         price: 250000,
//         description: "Tas ransel merajut yang praktis dan stylish",
//         image: "fas fa-bag-shopping",
//         color: "linear-gradient(135deg, #a8caba 0%, #5d4e75 100%)"
//     },
//     {
//         id: 9,
//         name: "Bandana Merajut",
//         category: "accessories",
//         price: 60000,
//         description: "Bandana merajut yang trendy dan multifungsi",
//         image: "fas fa-scarf",
//         color: "linear-gradient(135deg, #d299c2 0%, #fef9d7 100%)"
//     },
//     {
//         id: 10,
//         name: "Sweater Merajut",
//         category: "cardigan",
//         price: 400000,
//         description: "Sweater merajut yang hangat dan nyaman dipakai",
//         image: "fas fa-tshirt",
//         color: "linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)"
//     },
//     {
//         id: 11,
//         name: "Tas Tote Merajut",
//         category: "bag",
//         price: 180000,
//         description: "Tas tote merajut yang eco-friendly dan stylish",
//         image: "fas fa-bag-shopping",
//         color: "linear-gradient(135deg, #fdbb2d 0%, #22c1c3 100%)"
//     },
//     {
//         id: 12,
//         name: "Scarf Infinity",
//         category: "scarf",
//         price: 160000,
//         description: "Scarf infinity yang trendy dan mudah dipakai",
//         image: "fas fa-scarf",
//         color: "linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%)"
//     }
// ];

// Shopping cart
let cart = [];

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    displayProducts(products);
    setupEventListeners();
    updateCartCount();
});

// Setup event listeners
function setupEventListeners() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', filterProducts);
    
    // Category filter
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);
    
    // Price filter
    document.getElementById('priceFilter').addEventListener('change', filterProducts);
}

// Display products
function displayProducts(productsToShow) {
    const productsGrid = document.getElementById('productsGrid');
    const productCount = document.getElementById('productCount');
    
    productsGrid.innerHTML = '';
    productCount.textContent = productsToShow.length;
    
    productsToShow.forEach(product => {
        const productCard = createProductCard(product);
        productsGrid.appendChild(productCard);
    });
}

// Create product card
function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
        <div class="product-image" style="background: ${product.color}">
            <i class="${product.image}"></i>
        </div>
        <h3>${product.name}</h3>
        <p>${product.description}</p>
        <span class="price">Rp ${product.price.toLocaleString()}</span>
        <button class="btn btn-primary add-to-cart" onclick="addToCart(${product.id})">
            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
        </button>
    `;
    return card;
}

// Filter products
function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    
    let filteredProducts = products.filter(product => {
        // Search filter
        const matchesSearch = product.name.toLowerCase().includes(searchTerm) ||
                            product.description.toLowerCase().includes(searchTerm);
        
        // Category filter
        const matchesCategory = !categoryFilter || product.category === categoryFilter;
        
        // Price filter
        let matchesPrice = true;
        if (priceFilter) {
            const [min, max] = priceFilter.split('-').map(p => p === '+' ? Infinity : parseInt(p));
            matchesPrice = product.price >= min && (max === Infinity ? true : product.price <= max);
        }
        
        return matchesSearch && matchesCategory && matchesPrice;
    });
    
    displayProducts(filteredProducts);
}

// Add to cart
function addToCart(productId) {
    if (!window.isLoggedIn) {
        showNotification('Silakan login untuk menambah ke keranjang!', 'error');
        setTimeout(() => window.location.href = 'login.php', 1200);
        return;
    }
    const product = products.find(p => p.id === productId);
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            ...product,
            quantity: 1
        });
    }
    updateCartCount();
    showNotification('Produk berhasil ditambahkan ke keranjang!');
}

// Update cart count
function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
}

// Open cart modal
function openCart() {
    const cartModal = document.getElementById('cartModal');
    cartModal.style.display = 'flex';
    displayCartItems();
}

// Close cart modal
function closeCart() {
    const cartModal = document.getElementById('cartModal');
    cartModal.style.display = 'none';
}

// Display cart items
function displayCartItems() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Keranjang belanja kosong</p>';
        cartTotal.textContent = 'Rp 0';
        return;
    }
    
    cartItems.innerHTML = '';
    let total = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-info">
                <h4>${item.name}</h4>
                <p>Rp ${item.price.toLocaleString()}</p>
            </div>
            <div class="cart-item-quantity">
                <button onclick="updateQuantity(${item.id}, -1)">-</button>
                <span>${item.quantity}</span>
                <button onclick="updateQuantity(${item.id}, 1)">+</button>
            </div>
            <div class="cart-item-total">
                Rp ${itemTotal.toLocaleString()}
            </div>
            <button class="remove-item" onclick="removeFromCart(${item.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        cartItems.appendChild(cartItem);
    });
    
    cartTotal.textContent = `Rp ${total.toLocaleString()}`;
}

// Update quantity
function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            updateCartCount();
            displayCartItems();
        }
    }
}

// Remove from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartCount();
    displayCartItems();
}

// Checkout via WhatsApp
function checkout() {
    if (!window.isLoggedIn) {
        showNotification('Silakan login untuk checkout!', 'error');
        setTimeout(() => window.location.href = 'login.php', 1200);
        return;
    }
    if (cart.length === 0) {
        showNotification('Keranjang belanja kosong!', 'error');
        return;
    }
    
    // Redirect to checkout page
    window.location.href = 'checkout.php';
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Close cart when clicking outside
document.addEventListener('click', function(event) {
    const cartModal = document.getElementById('cartModal');
    if (event.target === cartModal) {
        closeCart();
    }
});

// Add notification styles
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification.success {
        background: #2ecc71;
    }
    
    .notification.error {
        background: #e74c3c;
    }
    
    .empty-cart {
        text-align: center;
        color: #666;
        padding: 2rem;
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
        gap: 1rem;
    }
    
    .cart-item-info {
        flex: 1;
    }
    
    .cart-item-info h4 {
        margin: 0 0 0.5rem 0;
        color: #2c3e50;
    }
    
    .cart-item-info p {
        margin: 0;
        color: #666;
    }
    
    .cart-item-quantity {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .cart-item-quantity button {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-item-quantity button:hover {
        background: #f8f9fa;
    }
    
    .cart-item-total {
        font-weight: 600;
        color: #e74c3c;
        min-width: 100px;
        text-align: right;
    }
    
    .remove-item {
        background: none;
        border: none;
        color: #e74c3c;
        cursor: pointer;
        padding: 0.5rem;
    }
    
    .remove-item:hover {
        color: #c0392b;
    }
    
    .cart-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        font-weight: 600;
        font-size: 1.2rem;
        border-top: 1px solid #eee;
    }
    
    .cart-footer {
        padding: 1rem;
        border-top: 1px solid #eee;
    }
    
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .close-cart {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }
    
    .close-cart:hover {
        color: #e74c3c;
    }
`;
document.head.appendChild(notificationStyles); 