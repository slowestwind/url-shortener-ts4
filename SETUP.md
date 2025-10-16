# TS4.in - Complete Setup Guide

## 🚀 Quick Start (Development)

### 1. Prerequisites
- PHP 8.4+
- Composer
- Node.js 18+
- MySQL 8.0+ (or SQLite for quick dev)

### 2. Install & Configure

```bash
# Clone the repository
git clone https://github.com/yourusername/ts4-in.git
cd ts4-in

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Create database
# For MySQL: create database ts4_db;
# For SQLite: database/database.sqlite (auto-created)

# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed

# Build frontend assets
npm run dev

# Start development server
php artisan serve
```

Access: **http://localhost:8000**

## 🔐 Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@ts4.in | password |
| User | john@example.com | password |

## 📦 Project Structure

```
ts4-in/
├── app/
│   ├── Http/Controllers/     # Request handlers
│   ├── Models/               # Database models
│   ├── Policies/             # Authorization policies
│   └── Actions/              # Business logic
├── database/
│   ├── migrations/           # Schema definitions
│   ├── seeders/              # Demo data
│   └── factories/            # Model factories
├── resources/
│   ├── views/                # Blade templates
│   ├── js/                   # Vue components
│   └── css/                  # Styles
├── routes/
│   ├── web.php               # Web routes
│   ├── auth.php              # Auth routes
│   └── api.php               # API routes (future)
├── storage/                  # App storage
├── tests/                    # Test suite
└── config/                   # Configuration files
```

## 🗄️ Database Schema

### Users Table
- id (Primary Key)
- workspace_id (Foreign Key)
- name
- email (Unique)
- password (hashed)
- role (admin, customer, guest)
- is_active
- permissions (JSON)
- timestamps

### Short Links Table
- id (Primary Key)
- user_id (Foreign Key)
- slug (Unique) - e.g., "abc123"
- custom_alias (Unique, Nullable)
- target_url
- title
- description
- category
- utm_params (JSON)
- click_count
- scheduled_at
- expires_at
- is_active
- qr_settings (JSON)
- qr_path
- timestamps

### Click Logs Table
- id (Primary Key)
- short_link_id (Foreign Key)
- ip_address
- user_agent
- referrer
- country
- city
- device_type
- browser_name
- os
- latitude
- longitude
- clicked_at (Timestamp)

### Profiles Table
- id (Primary Key)
- user_id (Foreign Key, Unique)
- bio
- avatar_url
- avatar_path
- profile_slug (Unique) - e.g., "@johndoe"
- display_name
- website_url
- social_links (JSON)
- theme_settings (JSON)
- show_analytics
- profile_views
- timestamps

## 🔑 API Routes Reference

### Public Routes
```
GET    /                           # Homepage
GET    /{slug}                     # Redirect short link
GET    /@{profileSlug}             # View public biolink
```

### Authenticated Routes
```
GET    /dashboard                  # User dashboard
GET    /links                      # List user links
POST   /links                      # Create link
GET    /links/{id}                 # View link details
PATCH  /links/{id}                 # Update link
DELETE /links/{id}                 # Delete link
GET    /links/{id}/qr              # Generate QR code
GET    /links/{id}/qr/download     # Download QR code
GET    /profile/edit               # Edit profile
PATCH  /profile                    # Update profile
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/LinkControllerTest.php
```

## 📄 Environment Variables

### Critical
```env
APP_NAME="TS4.in - Link Shortener"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_KEY=base64:XXXXX

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ts4_db
DB_USERNAME=root
DB_PASSWORD=

# Redis (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
CACHE_DRIVER=redis
```

## 🚀 Deployment

### Production Checklist
- [ ] Update `.env` with production settings
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Run `php artisan migrate --force`
- [ ] Run `npm run build`
- [ ] Setup SSL certificate
- [ ] Configure Nginx/Apache
- [ ] Setup Redis for caching
- [ ] Setup MySQL with backups
- [ ] Setup error monitoring
- [ ] Configure email service

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name ts4.in;
    root /var/www/ts4-in/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## 🐛 Troubleshooting

### Issue: "could not find driver"
**Solution:** Install PHP database extension
```bash
sudo apt-get install php8.4-mysql  # For MySQL
sudo apt-get install php8.4-sqlite  # For SQLite
```

### Issue: "Class not found"
**Solution:** Clear cache and regenerate autoloader
```bash
php artisan clear-all
composer dumpautoload
```

### Issue: Node modules issues
**Solution:** Clean and reinstall
```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js 3 Guide](https://vuejs.org)
- [Inertia.js Documentation](https://inertiajs.com)
- [Pest PHP Documentation](https://pestphp.com)

## 🤝 Need Help?

- 📧 Email: support@ts4.in
- 💬 GitHub Discussions: [Link](../../discussions)
- 🐛 Report Issues: [GitHub Issues](../../issues)

---

Happy coding! 🚀
