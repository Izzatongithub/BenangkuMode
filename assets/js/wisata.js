// Tourist Destinations Data
const destinationsData = [
    {
        id: 1,
        name: "Pantai Pink",
        category: "pantai",
        location: "Sekotong, Lombok Barat",
        description: "Pantai dengan pasir berwarna pink yang unik dan memukau",
        image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.8,
        reviews: 234,
        coordinates: { lat: -8.7893, lng: 115.9210 },
        address: "Sekotong, Lombok Barat, Nusa Tenggara Barat",
        operatingHours: "06:00 - 18:00",
        ticketPrice: "Rp 10.000",
        contact: "+62 812-3456-7890"
    },
    {
        id: 2,
        name: "Gunung Rinjani",
        category: "gunung",
        location: "Lombok Utara",
        description: "Gunung berapi aktif tertinggi kedua di Indonesia dengan pemandangan yang spektakuler",
        image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.9,
        reviews: 567,
        coordinates: { lat: -8.4111, lng: 116.4572 },
        address: "Lombok Utara, Nusa Tenggara Barat",
        operatingHours: "24 jam",
        ticketPrice: "Rp 150.000",
        contact: "+62 812-3456-7891"
    },
    {
        id: 3,
        name: "Air Terjun Tiu Kelep",
        category: "air-terjun",
        location: "Sembalun, Lombok Timur",
        description: "Air terjun setinggi 45 meter dengan kolam alami yang jernih",
        image: "https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.7,
        reviews: 189,
        coordinates: { lat: -8.4111, lng: 116.4572 },
        address: "Sembalun, Lombok Timur, Nusa Tenggara Barat",
        operatingHours: "07:00 - 17:00",
        ticketPrice: "Rp 15.000",
        contact: "+62 812-3456-7892"
    },
    {
        id: 4,
        name: "Desa Sade",
        category: "budaya",
        location: "Pujut, Lombok Tengah",
        description: "Desa adat Sasak yang masih mempertahankan tradisi dan arsitektur asli",
        image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.6,
        reviews: 156,
        coordinates: { lat: -8.7893, lng: 115.9210 },
        address: "Pujut, Lombok Tengah, Nusa Tenggara Barat",
        operatingHours: "08:00 - 17:00",
        ticketPrice: "Rp 20.000",
        contact: "+62 812-3456-7893"
    },
    {
        id: 5,
        name: "Warung Taliwang",
        category: "kuliner",
        location: "Mataram, Lombok",
        description: "Warung legendaris yang menyajikan ayam taliwang khas Lombok",
        image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.5,
        reviews: 298,
        coordinates: { lat: -8.5833, lng: 116.1167 },
        address: "Mataram, Lombok, Nusa Tenggara Barat",
        operatingHours: "10:00 - 22:00",
        ticketPrice: "Rp 25.000 - 50.000",
        contact: "+62 812-3456-7894"
    },
    {
        id: 6,
        name: "Pantai Gili Trawangan",
        category: "pantai",
        location: "Gili Trawangan, Lombok Utara",
        description: "Pulau kecil dengan pantai putih dan air jernih yang sempurna untuk snorkeling",
        image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.8,
        reviews: 445,
        coordinates: { lat: -8.3500, lng: 116.0333 },
        address: "Gili Trawangan, Lombok Utara, Nusa Tenggara Barat",
        operatingHours: "24 jam",
        ticketPrice: "Gratis",
        contact: "+62 812-3456-7895"
    },
    {
        id: 7,
        name: "Bukit Pergasingan",
        category: "gunung",
        location: "Sembalun, Lombok Timur",
        description: "Bukit dengan pemandangan sunrise yang memukau dan trekking yang menantang",
        image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.7,
        reviews: 234,
        coordinates: { lat: -8.4111, lng: 116.4572 },
        address: "Sembalun, Lombok Timur, Nusa Tenggara Barat",
        operatingHours: "24 jam",
        ticketPrice: "Rp 25.000",
        contact: "+62 812-3456-7896"
    },
    {
        id: 8,
        name: "Air Terjun Benang Stokel",
        category: "air-terjun",
        location: "Aikmel, Lombok Timur",
        description: "Air terjun bertingkat dengan pemandangan hutan yang asri",
        image: "https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.6,
        reviews: 167,
        coordinates: { lat: -8.5833, lng: 116.1167 },
        address: "Aikmel, Lombok Timur, Nusa Tenggara Barat",
        operatingHours: "07:00 - 17:00",
        ticketPrice: "Rp 10.000",
        contact: "+62 812-3456-7897"
    },
    {
        id: 9,
        name: "Pura Lingsar",
        category: "budaya",
        location: "Narmada, Lombok Barat",
        description: "Pura bersejarah yang menjadi simbol toleransi antara Hindu dan Islam",
        image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.4,
        reviews: 123,
        coordinates: { lat: -8.5833, lng: 116.1167 },
        address: "Narmada, Lombok Barat, Nusa Tenggara Barat",
        operatingHours: "08:00 - 17:00",
        ticketPrice: "Rp 15.000",
        contact: "+62 812-3456-7898"
    },
    {
        id: 10,
        name: "Sate Rembiga",
        category: "kuliner",
        location: "Rembiga, Lombok",
        description: "Sate kambing khas Lombok dengan bumbu rempah yang khas",
        image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        rating: 4.5,
        reviews: 189,
        coordinates: { lat: -8.5833, lng: 116.1167 },
        address: "Rembiga, Lombok, Nusa Tenggara Barat",
        operatingHours: "17:00 - 23:00",
        ticketPrice: "Rp 15.000 - 30.000",
        contact: "+62 812-3456-7899"
    }
];

// Global variables
let currentFilter = 'all';
let filteredDestinations = [...destinationsData];
let map;
let markers = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadDestinations();
    setupEventListeners();
    initMap();
});

// Setup event listeners
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', handleSearch);

    // Filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', handleFilter);
    });
}

// Handle search
function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterDestinations(currentFilter, searchTerm);
}

// Handle filter
function handleFilter(e) {
    const filter = e.target.dataset.filter;
    currentFilter = filter;
    
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    e.target.classList.add('active');
    
    // Filter destinations
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    filterDestinations(filter, searchTerm);
}

// Filter destinations
function filterDestinations(filter, searchTerm = '') {
    filteredDestinations = destinationsData.filter(destination => {
        const matchesFilter = filter === 'all' || destination.category === filter;
        const matchesSearch = destination.name.toLowerCase().includes(searchTerm) ||
                             destination.location.toLowerCase().includes(searchTerm) ||
                             destination.description.toLowerCase().includes(searchTerm);
        return matchesFilter && matchesSearch;
    });
    
    displayDestinations();
    updateMapMarkers();
}

// Display destinations
function displayDestinations() {
    const grid = document.getElementById('destinationsGrid');
    grid.innerHTML = '';
    
    if (filteredDestinations.length === 0) {
        grid.innerHTML = `
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>Tidak ada destinasi ditemukan</h3>
                <p>Coba ubah filter atau kata kunci pencarian Anda</p>
            </div>
        `;
        return;
    }
    
    filteredDestinations.forEach(destination => {
        const card = createDestinationCard(destination);
        grid.appendChild(card);
    });
}

// Create destination card
function createDestinationCard(destination) {
    const card = document.createElement('div');
    card.className = 'destination-card';
    card.innerHTML = `
        <div class="card-image">
            <img src="${destination.image}" alt="${destination.name}">
            <div class="card-overlay">
                <span class="category-badge ${destination.category}">${getCategoryName(destination.category)}</span>
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <span>${destination.rating}</span>
                    <span class="review-count">(${destination.reviews})</span>
                </div>
            </div>
        </div>
        <div class="card-content">
            <h3>${destination.name}</h3>
            <p class="location"><i class="fas fa-map-marker-alt"></i> ${destination.location}</p>
            <p class="description">${destination.description}</p>
            <div class="card-meta">
                <span class="price"><i class="fas fa-money-bill-wave"></i> ${destination.ticketPrice}</span>
                <span class="hours"><i class="fas fa-clock"></i> ${destination.operatingHours}</span>
            </div>
            <div class="card-actions">
                <a href="detail_wisata.php?id=${destination.id}" class="btn btn-primary">Lihat Detail</a>
                <button class="btn btn-secondary" onclick="openDirections(${destination.coordinates.lat}, ${destination.coordinates.lng})">
                    <i class="fas fa-directions"></i>
                </button>
            </div>
        </div>
    `;
    return card;
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

// Initialize Google Maps
function initMap() {
    // Center map on Lombok
    const lombokCenter = { lat: -8.5833, lng: 116.1167 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 9,
        center: lombokCenter,
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
    
    // Add markers for all destinations
    destinationsData.forEach(destination => {
        addMarker(destination);
    });
}

// Add marker to map
function addMarker(destination) {
    const marker = new google.maps.Marker({
        position: destination.coordinates,
        map: map,
        title: destination.name,
        icon: {
            url: getMarkerIcon(destination.category),
            scaledSize: new google.maps.Size(30, 30)
        }
    });
    
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div class="map-info-window">
                <h3>${destination.name}</h3>
                <p>${destination.location}</p>
                <p><strong>Rating:</strong> ${destination.rating} ⭐</p>
                <a href="detail_wisata.php?id=${destination.id}" class="map-link">Lihat Detail</a>
            </div>
        `
    });
    
    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });
    
    markers.push(marker);
}

// Get marker icon based on category
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

// Update map markers based on filtered destinations
function updateMapMarkers() {
    // Clear existing markers
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    // Add markers for filtered destinations
    filteredDestinations.forEach(destination => {
        addMarker(destination);
    });
}

// Open directions
function openDirections(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
}

// Load destinations on page load
function loadDestinations() {
    displayDestinations();
}

// Export for global use
window.openDirections = openDirections; 