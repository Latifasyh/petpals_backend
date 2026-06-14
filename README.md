🐾 PetPals - Backend

Bachelor Final Project (PFE) — International University of Rabat (UIR) — 2024

PetPals is a social platform for pet lovers. This repository contains the complete REST API backend built with Laravel and MySQL, powering all features including social networking, geolocation-based business discovery, messaging, and emergency services for pets.

🚀 Features

🔐 Complete Authentication — Laravel Sanctum token-based authentication
👤 User Profiles — Profile management with photo and cover image upload
🐕 Pets Management — Add and manage pets profiles
👥 Social Network — Friend requests, private messaging, notifications
💬 Discussion Feed — Posts, reactions, comments
🏢 Business Directory — Vets, pet stores, shelters, groomers
🛒 Marketplace — Products and sellers management
🗺️ Geolocation — Location-based business discovery
🏥 Veterinaries — Find and contact vets
🏠 Shelters & Groomers — Animal shelters and grooming services
🚨 Emergency Services — Urgent pet care access
📸 Media Management — Photo galleries for users and businesses


🛠️ Tech Stack
LayerTechnologyFrameworkLaravel (PHP)AuthenticationLaravel SanctumDatabaseMySQLAPIRESTful APIArchitectureMVCTestingPHPUnit

📁 Project Structure
petpals_backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── UserController.php
│   │   │   ├── UserPicturesController.php
│   │   │   ├── CoverPicController.php
│   │   │   ├── PetController.php
│   │   │   ├── DiscussionController.php
│   │   │   ├── PostController.php
│   │   │   ├── ReactionController.php
│   │   │   ├── ProductController.php
│   │   │   ├── SellerController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── VetoController.php
│   │   │   ├── SheltterGroomerController.php
│   │   │   ├── ProfessionTypesController.php
│   │   │   └── PicturesBusinessController.php
│   │   └── Middleware/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
├── tests/
├── .env.example
└── composer.json

⚙️ Installation & Setup
Prerequisites

PHP >= 8.1
Composer
MySQL
Laravel CLI

Steps
# Clone the repository
git clone https://github.com/Latifasyh/petpals_backend.git
cd petpals_backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=petpals
DB_USERNAME=root
DB_PASSWORD=

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Start the server
php artisan serve

---

## 🔑 API Controllers

| Controller | Responsibility |
|---|---|
| `AuthController` | Register, Login, Logout |
| `UserController` | User profile management |
| `UserPicturesController` | User photo uploads |
| `CoverPicController` | Cover image management |
| `PetController` | Pets profiles |
| `DiscussionController` | Discussions & comments |
| `PostController` | Posts & social feed |
| `ReactionController` | Likes & reactions |
| `ProductController` | Marketplace products |
| `SellerController` | Sellers management |
| `ServiceController` | Pet services |
| `VetoController` | Veterinaries |
| `SheltterGroomerController` | Shelters & groomers |
| `ProfessionTypesController` | Business categories |
| `PicturesBusinessController` | Business photo galleries |

---


👩‍💻 Developer
Latifa Sayah

🎓 Bachelor in Computer Engineering — UIR Rabat, 2024
💼 Full Stack Developer
🔗 LinkedIn : https://www.linkedin.com/in/latifa-sayah-16b9ab82/
🐙 GitHub : https://github.com/Latifasyh
📧 latifa-sayah001@hotmail.fr


🔗 Related Repository

🖥️ Frontend → PPLS_Frontend
https://github.com/Latifasyh/PPLS_Frontend


Built with ❤️ as Bachelor Final Project (PFE) — 2024

Developed entirely solo while working full time.

