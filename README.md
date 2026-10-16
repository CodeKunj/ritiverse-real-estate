# RiTiVerse — Luxury Real Estate Platform

A curated web platform showcasing India's most exclusive coastal and metropolitan landmark residences — from Mumbai's iconic coastline to Goa's private estates.

## 🌟 Highlights

- **Luxury Design Aesthetic**: Clean, high-end visual presentation with smooth animations, high-resolution media galleries, and responsive typography.
- **Dynamic Property Showcase**: Comprehensive residence profiles featuring specifications, pricing in Indian Rupees (INR), interactive image sliders, and location details.
- **Modular Architecture**: Built with modular PHP components (`includes/hero.php`, `includes/services.php`, `includes/architecture.php`, `includes/core_features.php`, etc.).
- **Enquiry Management**: Asynchronous AJAX enquiry submission handled via `enquire_handler.php`.
- **Config-Driven**: Centralized property details and configuration in `config.php`.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x
- **Frontend**: HTML5, Vanilla Modern CSS, JavaScript (Vanilla ES6+)
- **Database**: MySQL / MariaDB (via phpMyAdmin / PDO)
- **Admin Panel**: Executive CMS & Inquiries Console (`/admin`)

## 🚀 Getting Started

### Prerequisites

- PHP 8.x installed with PDO MySQL and GD extensions
- MySQL / MariaDB (XAMPP / WampServer / phpMyAdmin)

### Database Setup (phpMyAdmin)

1. Start **Apache** & **MySQL** in XAMPP.
2. Open **`http://localhost/phpmyadmin/`**.
3. Click **Import**, choose **`database.sql`**, and click **Import / Go**.
   *(Creates database `ritivers_realestate` and pre-loads all luxury residences, CMS content, gallery items, and admin credentials).*

### Local Development Server

1. **Start PHP server:**
   ```bash
   php -S localhost:8000
   ```

2. **Access Website & Admin Panel:**
   - **Frontend**: `http://localhost:8000`
   - **Admin Panel**: `http://localhost:8000/admin`
   - **Default Admin Login**: `admin@ritiverse.com` / `admin123456`

---

Designed & Developed with ❤️ for **RiTiVerse**.
