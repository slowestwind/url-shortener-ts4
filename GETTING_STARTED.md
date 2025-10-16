# 🚀 TS4.in - Getting Started Guide

Welcome to **TS4.in**, the completely free, open-source India-focused link shortener and Linktree alternative!

This guide will help you get up and running in minutes.

---

## 📋 Prerequisites

Before starting, make sure you have:

- **PHP 8.4+** - [Download](https://www.php.net/downloads)
- **Composer** - [Download](https://getcomposer.org)
- **Node.js 18+** - [Download](https://nodejs.org)
- **MySQL 8.0+** (or SQLite for quick dev) - [Download](https://dev.mysql.com/downloads/mysql)
- **Git** - [Download](https://git-scm.com)

---

## ⚡ Quick Start (5 minutes)

### 1. Clone & Install

```bash
# Navigate to the project
cd url-shortner-ts4

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Setup Environment

```bash
# Copy environment file (already done)
cp .env.example .env

# Generate application key (already done)
php artisan key:generate
```

### 3. Database Setup

**Option A: MySQL (Recommended)**
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE ts4_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Update .env file with your MySQL credentials:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=ts4_db
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

**Option B: SQLite (Quick Development)**
```bash
# The database.sqlite file is auto-created
# Just update .env:
# DB_CONNECTION=sqlite
```

### 4. Run Migrations & Seeding

```bash
# Migrate database
php artisan migrate

# Seed with demo data
php artisan db:seed
```

### 5. Start Development Server

**Terminal 1 - PHP Server:**
```bash
php artisan serve
```

**Terminal 2 - Frontend Development:**
```bash
npm run dev
```

### 6. Access Application

- **Frontend:** http://localhost:8000
- **Admin:** admin@ts4.in / password
- **User:** john@example.com / password

---

## 📁 Project Structure

```
url-shortner-ts4/
├── app/
│   ├── Http/Controllers/        # Request handlers
│   │   ├── LinkController.php        # Link CRUD & redirect
│   │   ├── ProfileController.php     # Biolink profiles
│   │   └── QRCodeController.php      # QR generation
│   ├── Models/                  # Database models
│   │   ├── User.php
│   │   ├── ShortLink.php
│   │   ├── ClickLog.php
│   │   ├── Profile.php
│   │   ├── Workspace.php
│   │   └── Role.php
│   └── Policies/                # Authorization
│
├── database/
│   ├── migrations/              # Schema definitions
│   ├── seeders/                 # Demo data
│   └── factories/               # Model factories
│
├── routes/
│   ├── web.php                  # Web routes
│   └── auth.php                 # Auth routes
│
├── resources/
│   ├── js/                      # Vue components (to be created)
│   └── views/                   # Blade templates
│
├── tests/                       # Test files
├── storage/                     # Files & logs
├── public/                      # Web accessible files
│
├── README.md                    # Project overview
├── CONTRIBUTING.md              # Contribution guidelines
├── SETUP.md                     # Detailed setup
├── PROJECT_SUMMARY.md           # Architecture summary
└── docker-compose.yml           # Docker setup
```

---

## 🛣️ Key Routes

### Public Routes

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/` | Homepage |
| GET | `/{slug}` | Redirect to target URL |
| GET | `/@{slug}` | Public biolink profile |

### Authenticated Routes

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/dashboard` | User dashboard |
| GET | `/links` | List user's links |
| POST | `/links` | Create new link |
| GET | `/links/{id}` | View link details & analytics |
| PATCH | `/links/{id}` | Update link |
| DELETE | `/links/{id}` | Delete link |
| GET | `/links/{id}/qr` | Generate QR code |
| GET | `/links/{id}/qr/download` | Download QR code |
| GET | `/profile/edit` | Edit profile |
| PATCH | `/profile` | Update profile |

---

## 🔧 Common Commands

### Database

```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Run seeders
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=RoleSeeder
```

### Development

```bash
# Start development server
php artisan serve

# Build frontend
npm run dev

# Build for production
npm run build

# Watch for changes
npm run dev

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Tinker (Interactive Shell)

```bash
# Open Laravel Tinker
php artisan tinker

# Example commands:
App\Models\User::all();
App\Models\ShortLink::with('clickLogs')->get();
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/LinkControllerTest.php

# Run with coverage
php artisan test --coverage
```

---

## 🚀 Docker Setup (Optional)

```bash
# Start all services
docker-compose up -d

# Stop services
docker-compose down

# Access MySQL container
docker-compose exec db mysql -u root -p

# View logs
docker-compose logs -f app
```

---

## 📊 Database Models

### User
```php
User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role' => 'customer', // admin, customer, guest
]);
```

### ShortLink
```php
ShortLink::create([
    'user_id' => 1,
    'slug' => 'abc123',
    'target_url' => 'https://example.com',
    'title' => 'My Link',
    'is_active' => true,
]);
```

### Profile
```php
Profile::create([
    'user_id' => 1,
    'profile_slug' => 'johndoe',
    'bio' => 'Digital Creator',
    'display_name' => 'John Doe',
    'social_links' => [
        'twitter' => 'https://twitter.com/johndoe',
        'instagram' => 'https://instagram.com/johndoe',
    ],
]);
```

### ClickLog
```php
ClickLog::create([
    'short_link_id' => 1,
    'ip_address' => '192.168.1.1',
    'user_agent' => request()->userAgent(),
    'referrer' => request()->referrer(),
    'device_type' => 'mobile',
    'country' => 'IN',
    'clicked_at' => now(),
]);
```

---

## 🔐 Authentication

### Register New User
```php
// Manual registration
$user = User::create([
    'name' => 'New User',
    'email' => 'user@example.com',
    'password' => bcrypt('password'),
    'role' => 'customer',
]);

$user->profile()->create([
    'profile_slug' => 'newuser',
    'display_name' => 'New User',
]);
```

### Login
```php
// Use Laravel's built-in authentication
auth()->attempt([
    'email' => 'admin@ts4.in',
    'password' => 'password',
]);
```

---

## 🎯 Creating Your First Link

### Via PHP
```php
$user = User::find(1);
$link = $user->shortLinks()->create([
    'slug' => str()->random(6),
    'target_url' => 'https://example.com',
    'title' => 'My First Link',
    'is_active' => true,
]);
```

### Via HTTP Request
```bash
curl -X POST http://localhost:8000/links \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "target_url": "https://example.com",
    "title": "My Link"
  }'
```

---

## 🐛 Troubleshooting

### Issue: "Class not found"
```bash
composer dumpautoload
php artisan clear-all
```

### Issue: "Database connection refused"
- Check MySQL is running: `sudo service mysql status`
- Verify credentials in `.env`
- Check database exists: `mysql -u root -e "SHOW DATABASES;"`

### Issue: "Port 8000 already in use"
```bash
php artisan serve --port=8001
```

### Issue: Node modules error
```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

---

## 📚 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js 3 Guide](https://vuejs.org)
- [Inertia.js Documentation](https://inertiajs.com)
- [MySQL Documentation](https://dev.mysql.com/doc)

---

## 🤝 Contributing

Want to contribute? Great! Check out:
- [CONTRIBUTING.md](CONTRIBUTING.md) - Guidelines
- [GitHub Issues](https://github.com/slowestwind/url-shortner-ts4/issues) - Tasks to work on
- [Good First Issues](https://github.com/slowestwind/url-shortner-ts4/issues?q=label%3A"good+first+issue") - Start here!

---

## 📞 Support & Help

- 📧 Email: slowestwind@gmail.com
- 💬 GitHub Discussions: [Ask Questions](https://github.com/slowestwind/url-shortner-ts4/discussions)
- 🐛 Report Issues: [GitHub Issues](https://github.com/slowestwind/url-shortner-ts4/issues)
- 📖 Full Documentation: [SETUP.md](SETUP.md)

---

## 🎉 You're All Set!

Your TS4.in development environment is ready. Start building amazing features!

**Happy coding!** 🚀

---

*Last updated: October 16, 2025*
*Questions? Start a discussion or email us!*
