// Workshop data
const workshops = [
    {
        id: 1,
        title: "Workshop Scarf Merajut Dasar",
        date: "2024-02-15",
        time: "09:00 - 12:00",
        location: "Studio BenangkuMode, Lombok",
        price: 150000,
        maxParticipants: 15,
        currentParticipants: 8,
        level: "Pemula",
        description: "Belajar teknik dasar merajut scarf dengan motif tradisional Lombok. Cocok untuk pemula yang ingin memulai perjalanan merajut.",
        materials: ["Jarum rajut", "Benang wol", "Gunting", "Jarum jahit"],
        instructor: "Sarah Amalia",
        image: "fas fa-scarf",
        color: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)"
    },
    {
        id: 2,
        title: "Workshop Cardigan Merajut",
        date: "2024-02-20",
        time: "13:00 - 17:00",
        location: "Studio BenangkuMode, Lombok",
        price: 250000,
        maxParticipants: 12,
        currentParticipants: 5,
        level: "Menengah",
        description: "Workshop lanjutan untuk membuat cardigan merajut yang nyaman dan stylish. Peserta akan belajar teknik shaping dan finishing.",
        materials: ["Jarum rajut", "Benang wol premium", "Gunting", "Jarum jahit", "Marker"],
        instructor: "Budi Santoso",
        image: "fas fa-tshirt",
        color: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
    },
    {
        id: 3,
        title: "Workshop Tas Merajut Unik",
        date: "2024-02-25",
        time: "10:00 - 15:00",
        location: "Studio BenangkuMode, Lombok",
        price: 200000,
        maxParticipants: 10,
        currentParticipants: 3,
        level: "Pemula - Menengah",
        description: "Buat tas merajut yang kuat dan fashionable. Belajar teknik membuat tas yang tahan lama dan memiliki desain unik.",
        materials: ["Jarum rajut", "Benang katun", "Gunting", "Jarum jahit", "Lining"],
        instructor: "Dewi Sartika",
        image: "fas fa-bag-shopping",
        color: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
    },
    {
        id: 4,
        title: "Workshop Aksesoris Merajut",
        date: "2024-03-01",
        time: "14:00 - 16:00",
        location: "Studio BenangkuMode, Lombok",
        price: 100000,
        maxParticipants: 20,
        currentParticipants: 12,
        level: "Pemula",
        description: "Buat berbagai aksesoris merajut seperti topi, sarung tangan, dan bandana. Workshop yang menyenangkan dan produktif.",
        materials: ["Jarum rajut", "Benang wol", "Gunting", "Jarum jahit"],
        instructor: "Sarah Amalia",
        image: "fas fa-hat-cowboy",
        color: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)"
    }
];

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    displayWorkshops();
    setupFormHandling();
});

// Display workshops
function displayWorkshops() {
    const workshopsGrid = document.getElementById('workshopsGrid');
    workshopsGrid.innerHTML = '';
    
    workshops.forEach(workshop => {
        const workshopCard = createWorkshopCard(workshop);
        workshopsGrid.appendChild(workshopCard);
    });
}

// Create workshop card
function createWorkshopCard(workshop) {
    const card = document.createElement('div');
    card.className = 'workshop-card';
    
    const availableSpots = workshop.maxParticipants - workshop.currentParticipants;
    const isFull = availableSpots <= 0;
    const isAlmostFull = availableSpots <= 3 && availableSpots > 0;
    
    card.innerHTML = `
        <div class="workshop-image" style="background: ${workshop.color}">
            <i class="${workshop.image}"></i>
        </div>
        <div class="workshop-content">
            <h3>${workshop.title}</h3>
            <div class="workshop-meta">
                <p><i class="fas fa-calendar"></i> ${formatDate(workshop.date)}</p>
                <p><i class="fas fa-clock"></i> ${workshop.time}</p>
                <p><i class="fas fa-map-marker-alt"></i> ${workshop.location}</p>
                <p><i class="fas fa-user"></i> ${workshop.instructor}</p>
                <p><i class="fas fa-signal"></i> Level: ${workshop.level}</p>
            </div>
            <p class="workshop-description">${workshop.description}</p>
            <div class="workshop-materials">
                <h4>Materi yang Disediakan:</h4>
                <ul>
                    ${workshop.materials.map(material => `<li>${material}</li>`).join('')}
                </ul>
            </div>
            <div class="workshop-footer">
                <div class="workshop-price">
                    <span class="price">Rp ${workshop.price.toLocaleString()}</span>
                    <span class="spots ${isFull ? 'full' : isAlmostFull ? 'almost-full' : ''}">
                        ${isFull ? 'Penuh' : `${availableSpots} slot tersisa`}
                    </span>
                </div>
                <button class="btn btn-primary" onclick="registerWorkshop(${workshop.id})" ${isFull ? 'disabled' : ''}>
                    ${isFull ? 'Workshop Penuh' : 'Daftar Sekarang'}
                </button>
            </div>
        </div>
    `;
    
    return card;
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    return date.toLocaleDateString('id-ID', options);
}

// Register workshop
function registerWorkshop(workshopId) {
    const workshop = workshops.find(w => w.id === workshopId);
    if (!workshop) return;
    
    // Check if workshop is full
    const availableSpots = workshop.maxParticipants - workshop.currentParticipants;
    if (availableSpots <= 0) {
        showNotification('Workshop sudah penuh!', 'error');
        return;
    }
    
    // Set workshop title in form
    document.getElementById('workshopTitle').value = workshop.title;
    
    // Open registration modal
    openRegistration();
}

// Open registration modal
function openRegistration() {
    const modal = document.getElementById('registrationModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close registration modal
function closeRegistration() {
    const modal = document.getElementById('registrationModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('registrationForm').reset();
}

// Setup form handling
function setupFormHandling() {
    const form = document.getElementById('registrationForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm(form)) {
            submitRegistration(form);
        }
    });
}

// Validate form
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    // Validate email
    const email = form.querySelector('#participantEmail');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.value && !emailRegex.test(email.value)) {
        email.classList.add('error');
        showNotification('Format email tidak valid!', 'error');
        isValid = false;
    }
    
    // Validate phone
    const phone = form.querySelector('#participantPhone');
    const phoneRegex = /^[\d\s\-\+\(\)]+$/;
    if (phone.value && !phoneRegex.test(phone.value)) {
        phone.classList.add('error');
        showNotification('Format nomor telepon tidak valid!', 'error');
        isValid = false;
    }
    
    return isValid;
}

// Submit registration
function submitRegistration(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Add workshop title
    data.workshopTitle = document.getElementById('workshopTitle').value;
    
    // Simulate form submission
    showNotification('Mengirim pendaftaran...', 'info');
    
    setTimeout(() => {
        // Simulate success
        showNotification('Pendaftaran berhasil! Kami akan menghubungi Anda segera.', 'success');
        closeRegistration();
        
        // In a real application, you would send this data to your server
        console.log('Registration data:', data);
        
        // Send WhatsApp notification
        sendWhatsAppNotification(data);
    }, 2000);
}

// Send WhatsApp notification
function sendWhatsAppNotification(data) {
    const message = `Halo! Pendaftaran workshop baru:\n\n` +
                   `Workshop: ${data.workshopTitle}\n` +
                   `Nama: ${data.name}\n` +
                   `Email: ${data.email}\n` +
                   `Telepon: ${data.phone}\n` +
                   `Usia: ${data.age || 'Tidak diisi'}\n` +
                   `Level: ${data.experience}\n` +
                   `Kebutuhan Khusus: ${data.specialNeeds || 'Tidak ada'}\n\n` +
                   `Mohon konfirmasi pendaftaran ini.`;
    
    const whatsappUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
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

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('registrationModal');
    if (event.target === modal) {
        closeRegistration();
    }
});

// Add workshop styles
const workshopStyles = document.createElement('style');
workshopStyles.textContent = `
    .workshop-info {
        padding: 60px 0;
        background: #f8f9fa;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }
    
    .info-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
    }
    
    .info-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .info-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .info-card h3 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
        color: #2c3e50;
    }
    
    .info-card p {
        color: #666;
        line-height: 1.6;
    }
    
    .upcoming-workshops {
        padding: 80px 0;
    }
    
    .workshops-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
    }
    
    .workshop-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .workshop-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .workshop-image {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .workshop-image i {
        font-size: 4rem;
        color: white;
    }
    
    .workshop-content {
        padding: 2rem;
    }
    
    .workshop-content h3 {
        font-size: 1.4rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .workshop-meta {
        margin-bottom: 1.5rem;
    }
    
    .workshop-meta p {
        margin-bottom: 0.5rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .workshop-meta i {
        color: #e74c3c;
        width: 16px;
    }
    
    .workshop-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .workshop-materials {
        margin-bottom: 1.5rem;
    }
    
    .workshop-materials h4 {
        font-size: 1rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .workshop-materials ul {
        list-style: none;
        padding: 0;
    }
    
    .workshop-materials li {
        padding: 0.25rem 0;
        color: #666;
        position: relative;
        padding-left: 1.5rem;
    }
    
    .workshop-materials li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #2ecc71;
        font-weight: bold;
    }
    
    .workshop-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .workshop-price {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .workshop-price .price {
        font-size: 1.3rem;
        font-weight: 600;
        color: #e74c3c;
    }
    
    .workshop-price .spots {
        font-size: 0.9rem;
        color: #666;
    }
    
    .workshop-price .spots.almost-full {
        color: #f39c12;
        font-weight: 600;
    }
    
    .workshop-price .spots.full {
        color: #e74c3c;
        font-weight: 600;
    }
    
    .registration-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }
    
    .registration-content {
        background: white;
        border-radius: 15px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .registration-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
    }
    
    .registration-header h3 {
        margin: 0;
        color: #2c3e50;
    }
    
    .close-registration {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
        padding: 0.5rem;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }
    
    .close-registration:hover {
        background: #e74c3c;
        color: white;
    }
    
    .registration-form {
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #e74c3c;
    }
    
    .form-group input.error,
    .form-group select.error,
    .form-group textarea.error {
        border-color: #e74c3c;
        box-shadow: 0 0 5px rgba(231, 76, 60, 0.3);
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    
    .past-events {
        padding: 80px 0;
        background: #f8f9fa;
    }
    
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .event-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .event-card:hover {
        transform: translateY(-5px);
    }
    
    .event-image {
        height: 150px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .event-image i {
        font-size: 3rem;
        color: white;
    }
    
    .event-content {
        padding: 1.5rem;
    }
    
    .event-content h3 {
        font-size: 1.2rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .event-date,
    .event-location,
    .event-participants {
        margin-bottom: 0.5rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .event-date i,
    .event-location i,
    .event-participants i {
        color: #e74c3c;
        width: 16px;
    }
    
    .event-description {
        margin-top: 1rem;
        color: #666;
        line-height: 1.6;
    }
    
    .notification.info {
        background: #3498db;
    }
    
    @media (max-width: 768px) {
        .workshops-grid {
            grid-template-columns: 1fr;
        }
        
        .workshop-footer {
            flex-direction: column;
            align-items: stretch;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .events-grid {
            grid-template-columns: 1fr;
        }
    }
`;
document.head.appendChild(workshopStyles); 