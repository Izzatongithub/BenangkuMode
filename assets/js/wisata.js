// Tourist Destinations Data from Database
// destinationsData is now passed from PHP

// Global variables
let currentFilter = 'all';
let filteredDestinations = [];
let map;
let markers = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing wisata page...');
    console.log('destinationsData available:', typeof destinationsData !== 'undefined');
    
    if (typeof destinationsData !== 'undefined') {
        // Initialize with database data
        filteredDestinations = [...destinationsData];
        console.log('Filtered destinations initialized:', filteredDestinations.length);
        loadDestinations();
        setupEventListeners();
        initMap();
    } else {
        console.error('destinationsData is not defined!');
        // Show error message to user
        const grid = document.getElementById('destinationsGrid');
        if (grid) {
            grid.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error loading destinations</h3>
                    <p>Unable to load destination data. Please refresh the page.</p>
                </div>
            `;
        }
    }
});

// Setup event listeners
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }

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
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    filterDestinations(filter, searchTerm);
}

// Filter destinations
function filterDestinations(filter, searchTerm = '') {
    filteredDestinations = destinationsData.filter(destination => {
        const matchesFilter = filter === 'all' || getCategorySlug(destination.category_id) === filter;
        const matchesSearch = destination.name.toLowerCase().includes(searchTerm) ||
                             (destination.location && destination.location.toLowerCase().includes(searchTerm)) ||
                             destination.description.toLowerCase().includes(searchTerm);
        return matchesFilter && matchesSearch;
    });
    
    displayDestinations();
    updateMapMarkers();
}

// Display destinations
function displayDestinations() {
    const grid = document.getElementById('destinationsGrid');
    if (!grid) return;
    
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
    
    grid.innerHTML = filteredDestinations.map(destination => createDestinationCard(destination)).join('');
}

// Create destination card
function createDestinationCard(destination) {
    const imageUrl = destination.main_image ? `assets/images/destinations/${destination.main_image}` : 'assets/images/placeholder.jpg';
    const rating = parseFloat(destination.rating) || 0;
    const reviewCount = parseInt(destination.review_count) || 0;
    const categoryName = getCategoryName(destination.category_id);
    
    return `
        <div class="destination-card" data-category="${getCategorySlug(destination.category_id)}">
            <div class="card-image">
                <img src="${imageUrl}" alt="${destination.name}" onerror="this.src='assets/images/placeholder.jpg'">
                <div class="card-category">${categoryName}</div>
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <span>${rating.toFixed(1)}</span>
                    <small>(${reviewCount} ulasan)</small>
                </div>
            </div>
            <div class="card-content">
                <h3>${destination.name}</h3>
                <p class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    ${destination.location || 'Lokasi tidak tersedia'}
                </p>
                <p class="description">${truncateText(destination.description, 100)}</p>
                <div class="card-details">
                    ${destination.ticket_price ? `<div class="detail-item"><i class="fas fa-ticket-alt"></i> ${destination.ticket_price}</div>` : ''}
                    ${destination.operating_hours ? `<div class="detail-item"><i class="fas fa-clock"></i> ${destination.operating_hours}</div>` : ''}
                </div>
                <div class="card-actions">
                    <a href="detail_wisata.php?id=${destination.id}" class="btn-detail">Lihat Detail</a>
                    ${destination.latitude && destination.longitude ? 
                        `<button class="btn-direction" onclick="openDirections(${destination.latitude}, ${destination.longitude})">
                            <i class="fas fa-directions"></i> Petunjuk Arah
                        </button>` : ''
                    }
                </div>
            </div>
        </div>
    `;
}

// Fungsi helper untuk memotong deskripsi dengan rapi
function truncateText(text, maxLength) {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    const truncated = text.substr(0, maxLength);
    return truncated.substr(0, truncated.lastIndexOf(' ')) + '...';
}

// Get category name from category_id
function getCategoryName(categoryId) {
    const categories = {
        1: 'Pantai',
        2: 'Gunung',
        3: 'Kota',
        4: 'Budaya',
        5: 'Petualangan'
    };
    return categories[categoryId] || 'Lainnya';
}

// Get category slug from category_id
function getCategorySlug(categoryId) {
    const categorySlugs = {
        1: 'pantai',
        2: 'gunung',
        3: 'kota',
        4: 'budaya',
        5: 'petualangan'
    };
    return categorySlugs[categoryId] || 'lainnya';
}

// Initialize map
function initMap() {
    if (typeof google === 'undefined') {
        console.log('Google Maps not loaded');
        return;
    }
    
    const mapElement = document.getElementById('map');
    if (!mapElement) return;
    
    // Default center (Lombok)
    const defaultCenter = { lat: -8.5833, lng: 116.1167 };
    
    map = new google.maps.Map(mapElement, {
        zoom: 9,
        center: defaultCenter,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });
    
    // Add markers for all destinations
    destinationsData.forEach(destination => {
        if (destination.latitude && destination.longitude) {
            addMarker(destination);
        }
    });
}

// Add marker to map
function addMarker(destination) {
    if (!map || !destination.latitude || !destination.longitude) return;
    
    const marker = new google.maps.Marker({
        position: { lat: parseFloat(destination.latitude), lng: parseFloat(destination.longitude) },
        map: map,
        title: destination.name,
        icon: getMarkerIcon(destination.category_id)
    });
    
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div class="map-info-window">
                <h4>${destination.name}</h4>
                <p>${destination.location || ''}</p>
                <p><strong>Rating:</strong> ${parseFloat(destination.rating).toFixed(1)} ⭐</p>
                <a href="detail_wisata.php?id=${destination.id}" target="_blank">Lihat Detail</a>
            </div>
        `
    });
    
    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });
    
    markers.push(marker);
}

// Get marker icon based on category
function getMarkerIcon(categoryId) {
    const colors = {
        1: '#FF6B6B', // Pantai - Red
        2: '#4ECDC4', // Gunung - Teal
        3: '#45B7D1', // Kota - Blue
        4: '#96CEB4', // Budaya - Green
        5: '#FFEAA7'  // Petualangan - Yellow
    };
    
    const color = colors[categoryId] || '#95A5A6';
    
    return {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: color,
        fillOpacity: 0.8,
        strokeWeight: 2,
        strokeColor: '#FFFFFF',
        scale: 8
    };
}

// Update map markers based on filter
function updateMapMarkers() {
    // Clear existing markers
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    // Add filtered markers
    filteredDestinations.forEach(destination => {
        if (destination.latitude && destination.longitude) {
            addMarker(destination);
        }
    });
}

// Open directions in Google Maps
function openDirections(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
}

// Load destinations (called on page load)
function loadDestinations() {
    displayDestinations();
} 