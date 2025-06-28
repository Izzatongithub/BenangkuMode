// Tourist Destinations Data (same as wisata.js)
const destinationsData = [
    {
        id: 1,
        name: "Pantai Pink",
        category: "pantai",
        location: "Sekotong, Lombok Barat",
        description: "Pantai dengan pasir berwarna pink yang unik dan memukau. Pantai ini terkenal dengan pasirnya yang berwarna pink alami yang terbentuk dari serpihan karang merah. Air lautnya jernih dan cocok untuk berenang atau snorkeling. Suasana pantai yang tenang dan pemandangan sunset yang memukau membuat tempat ini menjadi favorit wisatawan.",
        image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        images: [
            "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
        ],
        rating: 4.8,
        reviews: 234,
        coordinates: { lat: -8.7893, lng: 115.9210 },
        address: "Sekotong, Lombok Barat, Nusa Tenggara Barat",
        operatingHours: "06:00 - 18:00",
        ticketPrice: "Rp 10.000",
        contact: "+62 812-3456-7890",
        features: ["Pasir Pink Alami", "Air Jernih", "Snorkeling", "Sunset View", "Parkir Luas"],
        tips: [
            "Kunjungi saat pagi atau sore untuk menghindari terik matahari",
            "Bawa air minum dan makanan ringan",
            "Gunakan tabir surya untuk melindungi kulit",
            "Bawa kamera untuk mengabadikan momen indah",
            "Respek terhadap lingkungan, jangan buang sampah sembarangan"
        ],
        facilities: ["Parkir Motor", "Parkir Mobil", "Warung Makan", "Toilet Umum", "Tempat Ganti", "Pemandu Wisata"]
    },
    {
        id: 2,
        name: "Gunung Rinjani",
        category: "gunung",
        location: "Lombok Utara",
        description: "Gunung berapi aktif tertinggi kedua di Indonesia dengan pemandangan yang spektakuler. Gunung Rinjani memiliki ketinggian 3.726 meter di atas permukaan laut dan merupakan destinasi favorit para pendaki. Di puncak gunung terdapat danau kawah yang indah bernama Segara Anak. Pendakian ke Gunung Rinjani membutuhkan persiapan fisik dan mental yang matang.",
        image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        images: [
            "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            "https://images.unsplash.com/photo-1464822759844-d150baec0134?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
        ],
        rating: 4.9,
        reviews: 567,
        coordinates: { lat: -8.4111, lng: 116.4572 },
        address: "Lombok Utara, Nusa Tenggara Barat",
        operatingHours: "24 jam",
        ticketPrice: "Rp 150.000",
        contact: "+62 812-3456-7891",
        features: ["Pendakian", "Danau Kawah", "Sunrise View", "Camping", "Hot Spring"],
        tips: [
            "Persiapkan fisik dan mental dengan baik sebelum mendaki",
            "Bawa perlengkapan mendaki yang lengkap",
            "Ikuti panduan pemandu lokal yang berpengalaman",
            "Perhatikan cuaca dan kondisi gunung",
            "Bawa persediaan makanan dan air yang cukup"
        ],
        facilities: ["Basecamp", "Pemandu Wisata", "Tenda Sewa", "Perlengkapan Mendaki", "Warung Makan", "Toilet Umum"]
    },
    {
        id: 3,
        name: "Air Terjun Tiu Kelep",
        category: "air-terjun",
        location: "Sembalun, Lombok Timur",
        description: "Air terjun setinggi 45 meter dengan kolam alami yang jernih. Air Terjun Tiu Kelep merupakan salah satu air terjun terindah di Lombok dengan air yang mengalir deras dari ketinggian. Suasana sekitar air terjun sangat sejuk dan nyaman untuk berendam. Lokasi air terjun dikelilingi oleh hutan yang asri dan alami.",
        image: "https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        images: [
            "https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            "https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            "https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
        ],
        rating: 4.7,
        reviews: 189,
        coordinates: { lat: -8.4111, lng: 116.4572 },
        address: "Sembalun, Lombok Timur, Nusa Tenggara Barat",
        operatingHours: "07:00 - 17:00",
        ticketPrice: "Rp 15.000",
        contact: "+62 812-3456-7892",
        features: ["Air Terjun Tinggi", "Kolam Alami", "Berendam", "Fotografi", "Trekking"],
        tips: [
            "Kunjungi saat musim kemarau untuk debit air yang optimal",
            "Bawa baju ganti dan handuk",
            "Hati-hati dengan batu licin di sekitar air terjun",
            "Jangan berenang terlalu dekat dengan air terjun",
            "Bawa kamera untuk mengabadikan momen"
        ],
        facilities: ["Parkir Motor", "Parkir Mobil", "Warung Makan", "Toilet Umum", "Tempat Ganti", "Pemandu Wisata"]
    }
    // Add more destinations as needed
];

// Global variables
let currentDestination = null;
let currentImageIndex = 0;
let detailMap = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadDestinationDetail();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Gallery modal close
    const modal = document.getElementById('galleryModal');
    const closeBtn = modal.querySelector('.close');
    
    closeBtn.addEventListener('click', closeGallery);
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeGallery();
        }
    });
    
    // Keyboard navigation for gallery
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === 'block') {
            if (e.key === 'Escape') {
                closeGallery();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            }
        }
    });
}

// Load destination detail
function loadDestinationDetail() {
    const urlParams = new URLSearchParams(window.location.search);
    const destinationId = parseInt(urlParams.get('id'));
    
    currentDestination = destinationsData.find(dest => dest.id === destinationId);
    
    if (!currentDestination) {
        showErrorPage();
        return;
    }
    
    displayDestinationDetail();
    initDetailMap();
}

// Display destination detail
function displayDestinationDetail() {
    // Update breadcrumb
    document.getElementById('destinationName').textContent = currentDestination.name;
    
    // Update main image
    document.getElementById('mainImage').src = currentDestination.image;
    document.getElementById('mainImage').alt = currentDestination.name;
    
    // Update title and meta
    document.getElementById('detailTitle').textContent = currentDestination.name;
    document.getElementById('categoryBadge').textContent = getCategoryName(currentDestination.category);
    document.getElementById('categoryBadge').className = `category-badge ${currentDestination.category}`;
    document.getElementById('rating').textContent = currentDestination.rating;
    
    // Update description
    document.getElementById('description').innerHTML = `
        <p>${currentDestination.description}</p>
    `;
    
    // Update features
    const featuresGrid = document.getElementById('featuresGrid');
    featuresGrid.innerHTML = currentDestination.features.map(feature => `
        <div class="feature-item">
            <i class="fas fa-check"></i>
            <span>${feature}</span>
        </div>
    `).join('');
    
    // Update location details
    document.getElementById('address').textContent = currentDestination.address;
    document.getElementById('operatingHours').textContent = currentDestination.operatingHours;
    document.getElementById('ticketPrice').textContent = currentDestination.ticketPrice;
    document.getElementById('contact').textContent = currentDestination.contact;
    
    // Update tips
    const tipsContent = document.getElementById('tipsContent');
    tipsContent.innerHTML = `
        <ul>
            ${currentDestination.tips.map(tip => `<li>${tip}</li>`).join('')}
        </ul>
    `;
    
    // Update facilities
    const facilitiesGrid = document.getElementById('facilitiesGrid');
    facilitiesGrid.innerHTML = currentDestination.facilities.map(facility => `
        <div class="facility-item">
            <i class="fas fa-check-circle"></i>
            <span>${facility}</span>
        </div>
    `).join('');
    
    // Load related destinations
    loadRelatedDestinations();
}

// Load related destinations
function loadRelatedDestinations() {
    const relatedDestinations = destinationsData
        .filter(dest => dest.id !== currentDestination.id && dest.category === currentDestination.category)
        .slice(0, 3);
    
    const relatedGrid = document.getElementById('relatedGrid');
    relatedGrid.innerHTML = relatedDestinations.map(dest => `
        <div class="related-card">
            <img src="${dest.image}" alt="${dest.name}">
            <div class="related-content">
                <h4>${dest.name}</h4>
                <p>${dest.location}</p>
                <a href="detail_wisata.php?id=${dest.id}" class="btn btn-sm">Lihat Detail</a>
            </div>
        </div>
    `).join('');
}

// Initialize detail map
function initDetailMap() {
    const mapElement = document.getElementById('detailMap');
    
    detailMap = new google.maps.Map(mapElement, {
        zoom: 15,
        center: currentDestination.coordinates,
        styles: [
            {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{ color: '#e9e9e9' }, { lightness: 17 }]
            },
            {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{ color: '#f5f5f5' }, { lightness: 20 }]
            }
        ]
    });
    
    // Add marker for current destination
    new google.maps.Marker({
        position: currentDestination.coordinates,
        map: detailMap,
        title: currentDestination.name,
        icon: {
            url: getMarkerIcon(currentDestination.category),
            scaledSize: new google.maps.Size(40, 40)
        }
    });
}

// Get category name
function getCategoryName(category) {
    const categories = {
        'pantai': 'Pantai',
        'gunung': 'Gunung',
        'air-terjun': 'Air Terjun',
        'budaya': 'Budaya',
        'kuliner': 'Kuliner'
    };
    return categories[category] || category;
}

// Get marker icon
function getMarkerIcon(category) {
    const icons = {
        'pantai': 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
        'gunung': 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
        'air-terjun': 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
        'budaya': 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
        'kuliner': 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png'
    };
    return icons[category] || 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
}

// Open gallery
function openGallery() {
    const modal = document.getElementById('galleryModal');
    const mainImage = document.getElementById('galleryMainImage');
    const thumbnails = document.getElementById('galleryThumbnails');
    
    currentImageIndex = 0;
    mainImage.src = currentDestination.images[0];
    mainImage.alt = currentDestination.name;
    
    // Create thumbnails
    thumbnails.innerHTML = currentDestination.images.map((image, index) => `
        <div class="thumbnail ${index === 0 ? 'active' : ''}" onclick="selectImage(${index})">
            <img src="${image}" alt="${currentDestination.name}">
        </div>
    `).join('');
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Close gallery
function closeGallery() {
    const modal = document.getElementById('galleryModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Select image
function selectImage(index) {
    currentImageIndex = index;
    const mainImage = document.getElementById('galleryMainImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    mainImage.src = currentDestination.images[index];
    
    // Update active thumbnail
    thumbnails.forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });
}

// Previous image
function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + currentDestination.images.length) % currentDestination.images.length;
    selectImage(currentImageIndex);
}

// Next image
function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % currentDestination.images.length;
    selectImage(currentImageIndex);
}

// Open directions
function openDirections() {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${currentDestination.coordinates.lat},${currentDestination.coordinates.lng}`;
    window.open(url, '_blank');
}

// Share location
function shareLocation() {
    const url = window.location.href;
    const text = `Kunjungi ${currentDestination.name} di ${currentDestination.location}`;
    
    if (navigator.share) {
        navigator.share({
            title: currentDestination.name,
            text: text,
            url: url
        });
    } else {
        // Fallback: copy to clipboard
        const shareText = `${text}\n\n${url}`;
        navigator.clipboard.writeText(shareText).then(() => {
            alert('Link telah disalin ke clipboard!');
        });
    }
}

// Show error page
function showErrorPage() {
    document.querySelector('.destination-detail').innerHTML = `
        <div class="error-page">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>Destinasi Tidak Ditemukan</h2>
            <p>Maaf, destinasi yang Anda cari tidak ditemukan.</p>
            <a href="wisata.php" class="btn btn-primary">Kembali ke Daftar Wisata</a>
        </div>
    `;
}

// Export functions for global use
window.openGallery = openGallery;
window.closeGallery = closeGallery;
window.selectImage = selectImage;
window.prevImage = prevImage;
window.nextImage = nextImage;
window.openDirections = openDirections;
window.shareLocation = shareLocation; 