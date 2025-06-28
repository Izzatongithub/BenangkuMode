# BenangkuMode - Company Profile Website

A comprehensive company profile website for BenangkuMode, a handcraft knitting business in Lombok, Indonesia. This website showcases the company's products, workshops, events, and local tourist destinations.

## 🌟 Features

### 🏠 Landing Page (`index.php`)
- Modern hero section with company introduction
- Feature highlights and benefits
- Product preview section
- Workshop preview section
- Responsive design with animations

### 📖 About Us (`about.php`)
- Company story and background
- Mission and vision statements
- Core values
- Team member profiles
- Company statistics and achievements

### 🛍️ Products (`products.php`)
- Product catalog with search and filter functionality
- Shopping cart system with add/remove/update features
- WhatsApp integration for purchases
- Product categories and pricing
- Responsive product grid

### 🎨 Workshop & Events (`workshop.php`)
- Workshop listings with details
- User registration system
- Past events showcase
- Registration form with validation
- WhatsApp notifications for registrations

### 🔮 Coming Soon (`comingsoon.php`)
- Product previews for upcoming releases
- Voting system with localStorage persistence
- Vote results display
- Newsletter signup functionality
- Interactive product cards

### 📸 Gallery (`gallery.php`)
- Photo gallery with filter options
- Lightbox modal for image viewing
- Gallery statistics
- Keyboard navigation support
- Responsive image grid

### 🗺️ Tourist Destinations (`wisata.php`)
- Lombok tourist destinations showcase
- Search and filter by category (Pantai, Gunung, Air Terjun, Budaya, Kuliner)
- Interactive Google Maps integration
- Destination cards with ratings and reviews
- Category-based filtering

### 📍 Destination Details (`detail_wisata.php`)
- Detailed destination information
- Address and contact details
- Google Maps location
- Image gallery with navigation
- Tips and facilities information
- Related destinations
- Social sharing functionality

## 🛠️ Technology Stack

- **Backend**: PHP
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Database**: MySQL (ready for integration)
- **Server**: XAMPP (Apache)
- **Maps**: Google Maps API
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Poppins)

## 📁 Project Structure

```
BenangkuMode/
├── index.php                 # Landing page
├── about.php                 # About us page
├── products.php              # Products catalog
├── workshop.php              # Workshop & events
├── comingsoon.php            # Coming soon products
├── gallery.php               # Photo gallery
├── wisata.php                # Tourist destinations
├── detail_wisata.php         # Destination details
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   └── js/
│       ├── script.js         # Main JavaScript
│       ├── products.js       # Products functionality
│       ├── workshop.js       # Workshop functionality
│       ├── comingsoon.js     # Coming soon functionality
│       ├── gallery.js        # Gallery functionality
│       ├── wisata.js         # Tourist destinations
│       └── detail_wisata.js  # Destination details
└── README.md                 # Project documentation
```

## 🚀 Installation & Setup

### Prerequisites
- XAMPP (Apache + MySQL)
- Web browser
- Google Maps API key (optional)

### Setup Instructions

1. **Install XAMPP**
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Start Apache server

2. **Clone/Download Project**
   - Place the project files in `C:\xampp\htdocs\BenangkuMode\`

3. **Access Website**
   - Open your browser
   - Navigate to `http://localhost/BenangkuMode/`

4. **Google Maps Integration (Optional)**
   - Get a Google Maps API key from [Google Cloud Console](https://console.cloud.google.com/)
   - Replace `YOUR_GOOGLE_MAPS_API_KEY` in the JavaScript files with your actual API key

## 🎨 Design Features

### Responsive Design
- Mobile-first approach
- Responsive grid layouts
- Flexible navigation
- Touch-friendly interfaces

### Modern UI/UX
- Clean and minimalist design
- Smooth animations and transitions
- Interactive elements
- Consistent color scheme
- Professional typography

### Performance Optimizations
- Optimized images
- Efficient CSS and JavaScript
- Lazy loading for images
- Minimal external dependencies

## 📱 Responsive Breakpoints

- **Desktop**: 1200px and above
- **Tablet**: 768px - 1199px
- **Mobile**: 320px - 767px

## 🔧 Customization

### Colors
The website uses a consistent color palette defined in `style.css`:
- Primary: `#667eea` (Blue)
- Secondary: `#764ba2` (Purple)
- Success: `#27ae60` (Green)
- Warning: `#f39c12` (Orange)
- Danger: `#e74c3c` (Red)

### Fonts
- Primary: Poppins (Google Fonts)
- Icons: Font Awesome 6.0

### Adding New Destinations
To add new tourist destinations, edit the `destinationsData` array in `assets/js/wisata.js` and `assets/js/detail_wisata.js`.

## 📞 Contact Information

- **Company**: BenangkuMode
- **Location**: Lombok, Nusa Tenggara Barat, Indonesia
- **Phone**: +62 812-3456-7890
- **Email**: info@benangkumode.com

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- Font Awesome for icons
- Google Fonts for typography
- Unsplash for sample images
- Google Maps for location services

---

**Note**: This is a static website template. For production use, consider implementing:
- Database integration for dynamic content
- User authentication system
- Admin panel for content management
- Payment gateway integration
- SEO optimization
- Security measures
