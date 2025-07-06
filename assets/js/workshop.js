// Workshop data
// const workshops = [
//     {
//         id: 1,
//         title: "Workshop Scarf Merajut Dasar",
//         date: "2024-02-15",
//         time: "09:00 - 12:00",
//         location: "Studio BenangkuMode, Lombok",
//         price: 150000,
//         maxParticipants: 15,
//         currentParticipants: 8,
//         level: "Pemula",
//         description: "Belajar teknik dasar merajut scarf dengan motif tradisional Lombok. Cocok untuk pemula yang ingin memulai perjalanan merajut.",
//         materials: ["Jarum rajut", "Benang wol", "Gunting", "Jarum jahit"],
//         instructor: "Sarah Amalia",
//         image: "fas fa-scarf",
//         color: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)"
//     },
//     {
//         id: 2,
//         title: "Workshop Cardigan Merajut",
//         date: "2024-02-20",
//         time: "13:00 - 17:00",
//         location: "Studio BenangkuMode, Lombok",
//         price: 250000,
//         maxParticipants: 12,
//         currentParticipants: 5,
//         level: "Menengah",
//         description: "Workshop lanjutan untuk membuat cardigan merajut yang nyaman dan stylish. Peserta akan belajar teknik shaping dan finishing.",
//         materials: ["Jarum rajut", "Benang wol premium", "Gunting", "Jarum jahit", "Marker"],
//         instructor: "Budi Santoso",
//         image: "fas fa-tshirt",
//         color: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
//     },
//     {
//         id: 3,
//         title: "Workshop Tas Merajut Unik",
//         date: "2024-02-25",
//         time: "10:00 - 15:00",
//         location: "Studio BenangkuMode, Lombok",
//         price: 200000,
//         maxParticipants: 10,
//         currentParticipants: 3,
//         level: "Pemula - Menengah",
//         description: "Buat tas merajut yang kuat dan fashionable. Belajar teknik membuat tas yang tahan lama dan memiliki desain unik.",
//         materials: ["Jarum rajut", "Benang katun", "Gunting", "Jarum jahit", "Lining"],
//         instructor: "Dewi Sartika",
//         image: "fas fa-bag-shopping",
//         color: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
//     },
//     {
//         id: 4,
//         title: "Workshop Aksesoris Merajut",
//         date: "2024-03-01",
//         time: "14:00 - 16:00",
//         location: "Studio BenangkuMode, Lombok",
//         price: 100000,
//         maxParticipants: 20,
//         currentParticipants: 12,
//         level: "Pemula",
//         description: "Buat berbagai aksesoris merajut seperti topi, sarung tangan, dan bandana. Workshop yang menyenangkan dan produktif.",
//         materials: ["Jarum rajut", "Benang wol", "Gunting", "Jarum jahit"],
//         instructor: "Sarah Amalia",
//         image: "fas fa-hat-cowboy",
//         color: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)"
//     }
// ];

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing workshop page');
    // displayWorkshops();
    setupFormHandling();
    console.log('Workshop page initialized');
});

// Display workshops
// function displayWorkshops() {
//     const workshopsGrid = document.getElementById('workshopsGrid');
//     workshopsGrid.innerHTML = '';
    
//     workshops.forEach(workshop => {
//         const workshopCard = createWorkshopCard(workshop);
//         workshopsGrid.appendChild(workshopCard);
//     });
// }

// Create workshop card
// function createWorkshopCard(workshop) {
//     const card = document.createElement('div');
//     card.className = 'workshop-card';
    
//     const availableSpots = workshop.maxParticipants - workshop.currentParticipants;
//     const isFull = availableSpots <= 0;
//     const isAlmostFull = availableSpots <= 3 && availableSpots > 0;
    
//     card.innerHTML = `
//         <div class="workshop-image" style="background: ${workshop.color}">
//             <i class="${workshop.image}"></i>
//         </div>
//         <div class="workshop-content">
//             <h3>${workshop.title}</h3>
//             <div class="workshop-meta">
//                 <p><i class="fas fa-calendar"></i> ${formatDate(workshop.date)}</p>
//                 <p><i class="fas fa-clock"></i> ${workshop.time}</p>
//                 <p><i class="fas fa-map-marker-alt"></i> ${workshop.location}</p>
//                 <p><i class="fas fa-user"></i> ${workshop.instructor}</p>
//                 <p><i class="fas fa-signal"></i> Level: ${workshop.level}</p>
//             </div>
//             <p class="workshop-description">${workshop.description}</p>
//             <div class="workshop-materials">
//                 <h4>Materi yang Disediakan:</h4>
//                 <ul>
//                     ${workshop.materials.map(material => `<li>${material}</li>`).join('')}
//                 </ul>
//             </div>
//             <div class="workshop-footer">
//                 <div class="workshop-price">
//                     <span class="price">Rp ${workshop.price.toLocaleString()}</span>
//                     <span class="spots ${isFull ? 'full' : isAlmostFull ? 'almost-full' : ''}">
//                         ${isFull ? 'Penuh' : `${availableSpots} slot tersisa`}
//                     </span>
//                 </div>
//                 <button class="btn btn-primary" onclick="registerWorkshop(${workshop.id})" ${isFull ? 'disabled' : ''}>
//                     ${isFull ? 'Workshop Penuh' : 'Daftar Sekarang'}
//                 </button>
//             </div>
//         </div>
//     `;
    
//     return card;
// }

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
    console.log('registerWorkshop called with ID:', workshopId);
    console.log('isLoggedIn:', window.isLoggedIn);
    
    if (!window.isLoggedIn) {
        showNotification('Silakan login untuk mendaftar workshop!', 'error');
        setTimeout(() => window.location.href = 'login.php', 1200);
        return;
    }
    
    // Find workshop data from the DOM
    const button = document.querySelector(`[onclick="registerWorkshop(${workshopId})"]`);
    if (!button) {
        console.error('Button not found for workshop ID:', workshopId);
        showNotification('Terjadi kesalahan saat membuka form pendaftaran!', 'error');
        return;
    }
    
    const workshopCard = button.closest('.workshop-card');
    if (!workshopCard) {
        console.error('Workshop card not found');
        showNotification('Terjadi kesalahan saat membuka form pendaftaran!', 'error');
        return;
    }
    
    const workshopTitle = workshopCard.querySelector('h3');
    if (!workshopTitle) {
        console.error('Workshop title not found');
        showNotification('Terjadi kesalahan saat membuka form pendaftaran!', 'error');
        return;
    }
    
    // Set workshop title in form
    const titleInput = document.getElementById('workshopTitle');
    if (titleInput) {
        titleInput.value = workshopTitle.textContent;
    }
    
    // Open registration modal
    openRegistration();
}

// Open registration modal
function openRegistration() {
    console.log('Opening registration modal');
    const modal = document.getElementById('registrationModal');
    if (!modal) {
        console.error('Registration modal not found');
        showNotification('Terjadi kesalahan saat membuka form pendaftaran!', 'error');
        return;
    }
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    console.log('Modal opened successfully');
}

// Close registration modal
function closeRegistration() {
    console.log('Closing registration modal');
    const modal = document.getElementById('registrationModal');
    if (!modal) {
        console.error('Registration modal not found');
        return;
    }
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    const form = document.getElementById('registrationForm');
    if (form) {
        form.reset();
    }
    console.log('Modal closed successfully');
}

// Setup form handling
function setupFormHandling() {
    console.log('Setting up form handling');
    const form = document.getElementById('registrationForm');
    
    if (!form) {
        console.error('Registration form not found');
        return;
    }
    
    form.addEventListener('submit', function(e) {
        console.log('Form submitted');
        e.preventDefault();
        
        if (validateForm(form)) {
            submitRegistration(form);
        }
    });
    
    console.log('Form handling setup complete');
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
    
    // Add workshop title to form data
    const workshopTitle = document.getElementById('workshopTitle').value;
    formData.append('workshopTitle', workshopTitle);
    
    // Debug: Log form data
    console.log('Submitting form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Show loading notification
    showNotification('Mengirim pendaftaran...', 'info');
    
    // Send data to server
    fetch('register_workshop.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text().then(text => {
            console.log('Response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Invalid JSON response: ' + text);
            }
        });
    })
    .then(result => {
        console.log('Parsed result:', result);
        if (result.success) {
            showNotification(result.message, 'success');
            closeRegistration();
            
            // Get workshop title from form data
            const workshopTitle = document.getElementById('workshopTitle').value;
            
            // Update workshop display with new slot count
            if (result.remaining_slots !== undefined) {
                updateWorkshopSlots(workshopTitle, result.remaining_slots);
            }
            
            // Handle payment flow based on workshop type
            const data = Object.fromEntries(formData);
            if (result.is_free) {
                // Free workshop - show WhatsApp confirmation
                showWhatsAppConfirmation(data);
            } else {
                // Paid workshop - show payment confirmation
                showPaymentConfirmation(data, result);
            }
        } else {
            showNotification(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengirim pendaftaran. Silakan coba lagi.', 'error');
    });
}

// Show WhatsApp confirmation dialog
function showWhatsAppConfirmation(data) {
    // Create confirmation modal
    const confirmationModal = document.createElement('div');
    confirmationModal.className = 'whatsapp-confirmation-modal';
    confirmationModal.innerHTML = `
        <div class="whatsapp-confirmation-content">
            <div class="whatsapp-confirmation-header">
                <h3>Pendaftaran Berhasil! 🎉</h3>
                <button class="close-whatsapp-confirmation" onclick="closeWhatsAppConfirmation()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="whatsapp-confirmation-body">
                <p>Pendaftaran workshop Anda telah berhasil disimpan.</p>
                <p>Apakah Anda ingin menghubungi admin untuk konfirmasi atau pertanyaan lebih lanjut?</p>
                <div class="whatsapp-options">
                    <button class="btn btn-success" onclick="sendWhatsAppNotification(${JSON.stringify(data).replace(/"/g, '&quot;')})">
                        <i class="fab fa-whatsapp"></i> Hubungi Admin via WhatsApp
                    </button>
                    <button class="btn btn-secondary" onclick="closeWhatsAppConfirmation()">
                        Tidak, Terima Kasih
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(confirmationModal);
    
    // Add styles for the modal
    const styles = document.createElement('style');
    styles.textContent = `
        .whatsapp-confirmation-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10002;
        }
        
        .whatsapp-confirmation-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .whatsapp-confirmation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
            border-radius: 15px 15px 0 0;
        }
        
        .whatsapp-confirmation-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .close-whatsapp-confirmation {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-whatsapp-confirmation:hover {
            background: #e74c3c;
            color: white;
        }
        
        .whatsapp-confirmation-body {
            padding: 2rem;
            text-align: center;
        }
        
        .whatsapp-confirmation-body p {
            margin-bottom: 1rem;
            color: #666;
            line-height: 1.6;
        }
        
        .whatsapp-options {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }
        
        .whatsapp-options .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .whatsapp-options .btn-success {
            background: #25d366;
            color: white;
        }
        
        .whatsapp-options .btn-success:hover {
            background: #128c7e;
            transform: translateY(-1px);
        }
        
        .whatsapp-options .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .whatsapp-options .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            .whatsapp-options {
                flex-direction: column;
            }
            
            .whatsapp-options .btn {
                width: 100%;
                justify-content: center;
            }
        }
    `;
    
    if (!document.querySelector('#whatsapp-confirmation-styles')) {
        styles.id = 'whatsapp-confirmation-styles';
        document.head.appendChild(styles);
    }
}

// Close WhatsApp confirmation
function closeWhatsAppConfirmation() {
    const modal = document.querySelector('.whatsapp-confirmation-modal');
    if (modal) {
        modal.remove();
    }
}

// Update workshop slots display
function updateWorkshopSlots(workshopTitle, remainingSlots) {
    // Find the workshop card by title
    const workshopCards = document.querySelectorAll('.workshop-card');
    workshopCards.forEach(card => {
        const titleElement = card.querySelector('h3');
        if (titleElement && titleElement.textContent.trim() === workshopTitle.trim()) {
            // Update slots display
            const slotsElement = card.querySelector('.slots');
            if (slotsElement) {
                if (remainingSlots <= 0) {
                    slotsElement.textContent = 'Penuh';
                    slotsElement.className = 'slots full';
                    
                    // Disable register button
                    const registerButton = card.querySelector('.btn-primary');
                    if (registerButton) {
                        registerButton.textContent = 'Workshop Penuh';
                        registerButton.disabled = true;
                    }
                } else if (remainingSlots <= 3) {
                    slotsElement.textContent = remainingSlots + ' slot tersisa';
                    slotsElement.className = 'slots almost-full';
                } else {
                    slotsElement.textContent = remainingSlots + ' slot tersisa';
                    slotsElement.className = 'slots';
                }
            }
        }
    });
}

// Show payment confirmation for paid workshops
function showPaymentConfirmation(data, result) {
    // Create payment confirmation modal
    const paymentModal = document.createElement('div');
    paymentModal.className = 'payment-confirmation-modal';
    paymentModal.innerHTML = `
        <div class="payment-confirmation-content">
            <div class="payment-confirmation-header">
                <h3>Pendaftaran Berhasil! 💳</h3>
                <button class="close-payment-confirmation" onclick="closePaymentConfirmation()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="payment-confirmation-body">
                <div class="payment-info">
                    <h4>Detail Pembayaran</h4>
                    <p><strong>Workshop:</strong> ${data.workshopTitle}</p>
                    <p><strong>Nama:</strong> ${data.name}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Total Bayar:</strong> Rp ${Number(result.workshop_price).toLocaleString()}</p>
                    <p><strong>Status:</strong> <span class="status-pending">Menunggu Pembayaran</span></p>
                </div>
                
                <div class="payment-methods">
                    <h4>Metode Pembayaran</h4>
                    <div class="payment-options">
                        <button class="payment-option" onclick="processPayment('transfer', ${result.registration_id})">
                            <i class="fas fa-university"></i>
                            <span>Transfer Bank</span>
                        </button>
                        <button class="payment-option" onclick="processPayment('ewallet', ${result.registration_id})">
                            <i class="fas fa-wallet"></i>
                            <span>E-Wallet</span>
                        </button>
                        <button class="payment-option" onclick="processPayment('whatsapp', ${result.registration_id})">
                            <i class="fab fa-whatsapp"></i>
                            <span>Bayar via WhatsApp</span>
                        </button>
                    </div>
                </div>
                
                <div class="payment-actions">
                    <button class="btn btn-secondary" onclick="closePaymentConfirmation()">
                        Bayar Nanti
                    </button>
                    <button class="btn btn-primary" onclick="viewPaymentInstructions(${result.registration_id})">
                        Lihat Instruksi Pembayaran
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(paymentModal);
    
    // Add styles for the payment modal
    const styles = document.createElement('style');
    styles.textContent = `
        .payment-confirmation-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10002;
        }
        
        .payment-confirmation-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .payment-confirmation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
            border-radius: 15px 15px 0 0;
        }
        
        .payment-confirmation-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .close-payment-confirmation {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-payment-confirmation:hover {
            background: #e74c3c;
            color: white;
        }
        
        .payment-confirmation-body {
            padding: 2rem;
        }
        
        .payment-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .payment-info h4 {
            margin: 0 0 1rem 0;
            color: #2c3e50;
        }
        
        .payment-info p {
            margin: 0.5rem 0;
            color: #666;
        }
        
        .status-pending {
            color: #f39c12;
            font-weight: 600;
        }
        
        .payment-methods h4 {
            margin: 0 0 1rem 0;
            color: #2c3e50;
        }
        
        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .payment-option {
            background: white;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .payment-option:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .payment-option i {
            font-size: 1.5rem;
            color: #667eea;
        }
        
        .payment-option span {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .payment-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        .payment-actions .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        
        @media (max-width: 768px) {
            .payment-options {
                grid-template-columns: 1fr;
            }
            
            .payment-actions {
                flex-direction: column;
            }
            
            .payment-actions .btn {
                width: 100%;
            }
        }
    `;
    
    if (!document.querySelector('#payment-confirmation-styles')) {
        styles.id = 'payment-confirmation-styles';
        document.head.appendChild(styles);
    }
}

// Close payment confirmation
function closePaymentConfirmation() {
    const modal = document.querySelector('.payment-confirmation-modal');
    if (modal) {
        modal.remove();
    }
}

// Process payment method selection
function processPayment(method, registrationId) {
    console.log('Processing payment:', method, 'for registration:', registrationId);
    
    switch (method) {
        case 'transfer':
            showTransferInstructions(registrationId);
            break;
        case 'ewallet':
            showEWalletInstructions(registrationId);
            break;
        case 'whatsapp':
            showWhatsAppPayment(registrationId);
            break;
    }
}

// Show transfer instructions
function showTransferInstructions(registrationId) {
    const message = `Instruksi Transfer Bank:\n\n` +
                   `Bank: BCA\n` +
                   `No. Rekening: 1234567890\n` +
                   `Atas Nama: BenangkuMode\n` +
                   `ID Registrasi: ${registrationId}\n\n` +
                   `Silakan transfer sesuai nominal dan kirim bukti transfer.`;
    
    alert(message);
}

// Show e-wallet instructions
function showEWalletInstructions(registrationId) {
    const message = `Instruksi E-Wallet:\n\n` +
                   `Pilih e-wallet favorit Anda:\n` +
                   `• GoPay\n` +
                   `• OVO\n` +
                   `• DANA\n` +
                   `• LinkAja\n\n` +
                   `ID Registrasi: ${registrationId}\n\n` +
                   `Silakan pilih metode pembayaran.`;
    
    alert(message);
}

// Show WhatsApp payment
function showWhatsAppPayment(registrationId) {
    const message = `Halo! Saya ingin melakukan pembayaran untuk workshop.\n\n` +
                   `ID Registrasi: ${registrationId}\n\n` +
                   `Mohon bantu proses pembayaran saya.`;
    
    const whatsappUrl = `https://wa.me/62895608491832?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

// View payment instructions
function viewPaymentInstructions(registrationId) {
    const message = `Detail Pembayaran untuk ID: ${registrationId}\n\n` +
                   `1. Transfer Bank BCA: 1234567890\n` +
                   `2. E-Wallet: Scan QR Code\n` +
                   `3. Bayar via WhatsApp\n\n` +
                   `Setelah pembayaran, kirim bukti transfer ke WhatsApp kami.`;
    
    alert(message);
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
    
    // Close the confirmation modal
    closeWhatsAppConfirmation();
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
    
    // Close WhatsApp confirmation modal when clicking outside
    const whatsappModal = document.querySelector('.whatsapp-confirmation-modal');
    if (event.target === whatsappModal) {
        closeWhatsAppConfirmation();
    }
    
    // Close payment confirmation modal when clicking outside
    const paymentModal = document.querySelector('.payment-confirmation-modal');
    if (event.target === paymentModal) {
        closePaymentConfirmation();
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