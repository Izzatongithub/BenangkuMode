// Coming soon products data
const comingSoonProducts = [
    {
        id: 1,
        name: "Sweater Merajut Oversize",
        description: "Sweater merajut dengan model oversize yang trendy dan nyaman dipakai. Cocok untuk cuaca dingin dengan desain yang fashionable.",
        category: "Pakaian",
        estimatedPrice: "Rp 450.000",
        releaseDate: "Maret 2024",
        votes: 45,
        image: "fas fa-tshirt",
        color: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
        features: ["Oversize fit", "Bahan wol premium", "Desain modern", "Warna pilihan"]
    },
    {
        id: 2,
        name: "Tas Merajut Crossbody",
        description: "Tas crossbody merajut yang praktis dan stylish. Dilengkapi dengan lining dan kompartemen yang terorganisir dengan baik.",
        category: "Tas",
        estimatedPrice: "Rp 280.000",
        releaseDate: "April 2024",
        votes: 38,
        image: "fas fa-bag-shopping",
        color: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        features: ["Crossbody style", "Lining waterproof", "Kompartemen dalam", "Adjustable strap"]
    },
    {
        id: 3,
        name: "Set Scarf & Beanie",
        description: "Set lengkap scarf dan beanie yang cocok dipakai bersama. Desain yang harmonis dengan motif yang serasi.",
        category: "Aksesoris",
        estimatedPrice: "Rp 220.000",
        releaseDate: "Februari 2024",
        votes: 52,
        image: "fas fa-scarf",
        color: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
        features: ["Set lengkap", "Motif serasi", "Bahan hangat", "Ukuran universal"]
    },
    {
        id: 4,
        name: "Cardigan Merajut Crop",
        description: "Cardigan crop merajut yang perfect untuk layering. Desain yang modern dan cocok untuk berbagai kesempatan.",
        category: "Pakaian",
        estimatedPrice: "Rp 320.000",
        releaseDate: "Maret 2024",
        votes: 41,
        image: "fas fa-tshirt",
        color: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)",
        features: ["Crop length", "Button closure", "Pocket detail", "Versatile design"]
    },
    {
        id: 5,
        name: "Tas Merajut Backpack",
        description: "Backpack merajut yang kuat dan nyaman dipakai. Ideal untuk aktivitas sehari-hari dengan kapasitas yang cukup.",
        category: "Tas",
        estimatedPrice: "Rp 350.000",
        releaseDate: "April 2024",
        votes: 29,
        image: "fas fa-bag-shopping",
        color: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
        features: ["Backpack style", "Reinforced bottom", "Multiple pockets", "Padded straps"]
    },
    {
        id: 6,
        name: "Sarung Tangan Merajut Fingerless",
        description: "Sarung tangan fingerless yang trendy dan fungsional. Memungkinkan penggunaan gadget sambil tetap hangat.",
        category: "Aksesoris",
        estimatedPrice: "Rp 120.000",
        releaseDate: "Februari 2024",
        votes: 35,
        image: "fas fa-hand-paper",
        color: "linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)",
        features: ["Fingerless design", "Touchscreen compatible", "Elastic cuff", "Grippy palm"]
    }
];

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    displayComingSoonProducts();
    displayVoteResults();
    setupNewsletterForm();
});

// Display coming soon products
function displayComingSoonProducts() {
    const grid = document.getElementById('comingSoonGrid');
    grid.innerHTML = '';
    
    comingSoonProducts.forEach(product => {
        const productCard = createComingSoonCard(product);
        grid.appendChild(productCard);
    });
}

// Create coming soon product card
function createComingSoonCard(product) {
    const card = document.createElement('div');
    card.className = 'coming-soon-card';
    
    card.innerHTML = `
        <div class="product-image" style="background: ${product.color}">
            <i class="${product.image}"></i>
            <div class="coming-soon-badge">Coming Soon</div>
        </div>
        <div class="product-content">
            <h3>${product.name}</h3>
            <p class="product-description">${product.description}</p>
            <div class="product-meta">
                <span class="category"><i class="fas fa-tag"></i> ${product.category}</span>
                <span class="price"><i class="fas fa-tag"></i> ${product.estimatedPrice}</span>
                <span class="release-date"><i class="fas fa-calendar"></i> ${product.releaseDate}</span>
            </div>
            <div class="product-features">
                <h4>Fitur Utama:</h4>
                <ul>
                    ${product.features.map(feature => `<li>${feature}</li>`).join('')}
                </ul>
            </div>
            <div class="vote-section">
                <div class="vote-count">
                    <i class="fas fa-heart"></i>
                    <span>${product.votes} votes</span>
                </div>
                <button class="btn btn-primary vote-btn" onclick="voteProduct(${product.id})">
                    <i class="fas fa-thumbs-up"></i> Vote
                </button>
            </div>
        </div>
    `;
    
    return card;
}

// Vote for a product
function voteProduct(productId) {
    const product = comingSoonProducts.find(p => p.id === productId);
    if (!product) return;
    
    // Check if user has already voted (using localStorage)
    const votedProducts = JSON.parse(localStorage.getItem('votedProducts') || '[]');
    
    if (votedProducts.includes(productId)) {
        showNotification('Anda sudah memberikan vote untuk produk ini!', 'error');
        return;
    }
    
    // Add vote
    product.votes++;
    votedProducts.push(productId);
    localStorage.setItem('votedProducts', JSON.stringify(votedProducts));
    
    // Update display
    displayComingSoonProducts();
    displayVoteResults();
    
    showNotification('Terima kasih atas vote Anda!', 'success');
}

// Display vote results
function displayVoteResults() {
    const resultsGrid = document.getElementById('resultsGrid');
    resultsGrid.innerHTML = '';
    
    // Sort products by votes (descending)
    const sortedProducts = [...comingSoonProducts].sort((a, b) => b.votes - a.votes);
    
    // Calculate total votes
    const totalVotes = sortedProducts.reduce((sum, product) => sum + product.votes, 0);
    
    sortedProducts.forEach((product, index) => {
        const percentage = totalVotes > 0 ? Math.round((product.votes / totalVotes) * 100) : 0;
        const resultCard = createResultCard(product, index + 1, percentage);
        resultsGrid.appendChild(resultCard);
    });
}

// Create result card
function createResultCard(product, rank, percentage) {
    const card = document.createElement('div');
    card.className = 'result-card';
    
    const rankClass = rank === 1 ? 'first' : rank === 2 ? 'second' : rank === 3 ? 'third' : '';
    
    card.innerHTML = `
        <div class="result-rank ${rankClass}">
            <span class="rank-number">#${rank}</span>
        </div>
        <div class="result-content">
            <h3>${product.name}</h3>
            <div class="result-stats">
                <div class="vote-bar">
                    <div class="vote-progress" style="width: ${percentage}%"></div>
                </div>
                <div class="vote-info">
                    <span class="vote-count">${product.votes} votes</span>
                    <span class="vote-percentage">${percentage}%</span>
                </div>
            </div>
            <p class="release-info">Rilis: ${product.releaseDate}</p>
        </div>
    `;
    
    return card;
}

// Setup newsletter form
function setupNewsletterForm() {
    const form = document.getElementById('newsletterForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('newsletterEmail').value;
        
        if (!email) {
            showNotification('Mohon masukkan email Anda!', 'error');
            return;
        }
        
        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showNotification('Format email tidak valid!', 'error');
            return;
        }
        
        // Simulate newsletter signup
        showNotification('Mendaftar newsletter...', 'info');
        
        setTimeout(() => {
            showNotification('Berhasil berlangganan newsletter!', 'success');
            form.reset();
            
            // In a real application, you would send this to your server
            console.log('Newsletter signup:', email);
        }, 1500);
    });
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
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Add coming soon styles
const comingSoonStyles = document.createElement('style');
comingSoonStyles.textContent = `
    .coming-soon-info {
        padding: 60px 0;
        background: #f8f9fa;
    }
    
    .info-content {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .info-content h2 {
        font-size: 2.5rem;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }
    
    .info-content p {
        font-size: 1.1rem;
        color: #666;
        line-height: 1.8;
        margin-bottom: 2rem;
    }
    
    .info-features {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .info-features .feature {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #e74c3c;
        font-weight: 600;
    }
    
    .info-features i {
        font-size: 1.2rem;
    }
    
    .coming-soon-products {
        padding: 80px 0;
    }
    
    .coming-soon-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }
    
    .coming-soon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .coming-soon-card .product-image {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .coming-soon-card .product-image i {
        font-size: 3rem;
        color: white;
    }
    
    .coming-soon-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .coming-soon-card .product-content {
        padding: 2rem;
    }
    
    .coming-soon-card h3 {
        font-size: 1.3rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .product-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .product-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .product-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.9rem;
    }
    
    .product-meta i {
        color: #e74c3c;
    }
    
    .product-features {
        margin-bottom: 1.5rem;
    }
    
    .product-features h4 {
        font-size: 1rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .product-features ul {
        list-style: none;
        padding: 0;
    }
    
    .product-features li {
        padding: 0.25rem 0;
        color: #666;
        position: relative;
        padding-left: 1.5rem;
    }
    
    .product-features li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #2ecc71;
        font-weight: bold;
    }
    
    .vote-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .vote-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #e74c3c;
        font-weight: 600;
    }
    
    .vote-btn {
        padding: 8px 20px;
        font-size: 0.9rem;
    }
    
    .vote-results {
        padding: 80px 0;
        background: #f8f9fa;
    }
    
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .result-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: transform 0.3s ease;
    }
    
    .result-card:hover {
        transform: translateY(-3px);
    }
    
    .result-rank {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        color: white;
        flex-shrink: 0;
    }
    
    .result-rank.first {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    }
    
    .result-rank.second {
        background: linear-gradient(135deg, #c0c0c0 0%, #e5e5e5 100%);
    }
    
    .result-rank.third {
        background: linear-gradient(135deg, #cd7f32 0%, #daa520 100%);
    }
    
    .result-rank:not(.first):not(.second):not(.third) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .result-content {
        flex: 1;
    }
    
    .result-content h3 {
        font-size: 1.2rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .vote-bar {
        width: 100%;
        height: 8px;
        background: #eee;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .vote-progress {
        height: 100%;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .vote-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
    }
    
    .vote-count {
        color: #666;
    }
    
    .vote-percentage {
        color: #e74c3c;
        font-weight: 600;
    }
    
    .release-info {
        margin-top: 0.5rem;
        color: #666;
        font-size: 0.9rem;
    }
    
    .newsletter-signup {
        padding: 80px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .newsletter-content {
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .newsletter-content h2 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .newsletter-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .newsletter-form {
        display: flex;
        gap: 1rem;
        max-width: 400px;
        margin: 0 auto;
    }
    
    .newsletter-form .form-group {
        display: flex;
        gap: 1rem;
        width: 100%;
    }
    
    .newsletter-form input {
        flex: 1;
        padding: 15px 20px;
        border: none;
        border-radius: 25px;
        font-size: 1rem;
    }
    
    .newsletter-form input:focus {
        outline: none;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
    }
    
    .newsletter-form button {
        padding: 15px 25px;
        border-radius: 25px;
        white-space: nowrap;
    }
    
    .notification.info {
        background: #3498db;
    }
    
    @media (max-width: 768px) {
        .info-features {
            flex-direction: column;
            align-items: center;
        }
        
        .results-grid {
            grid-template-columns: 1fr;
        }
        
        .result-card {
            flex-direction: column;
            text-align: center;
        }
        
        .newsletter-form {
            flex-direction: column;
        }
        
        .newsletter-form .form-group {
            flex-direction: column;
        }
    }
`;
document.head.appendChild(comingSoonStyles); 