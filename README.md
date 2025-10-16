# TS4.in - FREE FOREVER Link Shortener 🔗

**Completely free, open-source alternative to Linktree / Bitly / QR generators.**

[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
[![Hacktoberfest](https://img.shields.io/badge/Hacktoberfest-friendly-orange)](CONTRIBUTING.md)

## 🎯 Features (MVP)

- ✅ **URL Shortening** - Auto-generated & custom aliases
- ✅ **QR Code Generation** - Download as PNG/SVG  
- ✅ **Biolink Pages** - Linktree alternative with customizable profiles
- ✅ **Click Analytics** - Track clicks with IP, device, location, referrer
- ✅ **Role-Based Access** - Admin, Customer, Guest roles
- ✅ **Rate Limiting** - Redis-backed spam protection
- ✅ **Workspace Support** - Multi-workspace management

## 🚀 Tech Stack

- **Backend:** Laravel 12 + PHP 8.4
- **Frontend:** Vue.js 3 + Inertia.js
- **Database:** MySQL 8 + Redis
- **Server:** Nginx
- **Testing:** PestPHP

## 📋 Prerequisites

- PHP 8.4+
- Composer
- Node.js 18+
- MySQL 8+
- Redis (optional, for caching)

## 🔧 Installation

1. **Clone & Setup**
```bash
git clone git@github.com:slowestwind/url-shortner-ts4.git
cd url-shortner-ts4
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**
```bash
# Update .env with your MySQL credentials
DB_CONNECTION=mysql
DB_DATABASE=ts4_db
DB_USERNAME=root
```

5. **Migrate & Seed**
```bash
php artisan migrate:fresh --seed
php artisan db:seed
```

6. **Build & Run**
```bash
npm run dev
php artisan serve
```

Visit: **http://localhost:8000**

## 👥 Default Credentials

- **Admin:** admin@ts4.in / password
- **User:** john@example.com / password

## 📁 Project Structure

```
ts4-in/
├── app/
│   ├── Models/          # Eloquent Models
│   ├── Http/Controllers # Controllers
│   ├── Policies         # Authorization Policies
│   └── Middleware
├── database/
│   ├── migrations/      # Database Migrations
│   ├── seeders/         # Database Seeders
│   └── factories/       # Model Factories
├── resources/
│   ├── views/           # Inertia Vue Components
│   └── js/              # Vue.js Frontend
├── routes/              # Route definitions
└── tests/               # PestPHP Tests
```

## 🔑 Key Models

| Model | Purpose |
|-------|---------|
| `User` | User accounts with roles |
| `Workspace` | Team/organization container |
| `ShortLink` | URL shortening data |
| `ClickLog` | Click analytics & tracking |
| `Profile` | User biolink profile |
| `Role` | Role-based permissions |

## 🎨 API Routes

### Links
- `GET /links` - List user's links
- `POST /links` - Create new link
- `GET /links/{id}` - View link analytics
- `PATCH /links/{id}` - Update link
- `DELETE /links/{id}` - Delete link
- `GET /{slug}` - Redirect to target URL

### Public Biolink
- `GET /@{profileSlug}` - View public profile

### QR Codes
- `GET /links/{id}/qr` - Generate QR code
- `GET /links/{id}/qr/download` - Download QR code as PNG

## 🧪 Testing

```bash
php artisan test
# or with PestPHP
./vendor/bin/pest
```

## 🚀 Roadmap

### Phase 2
- [ ] Advanced analytics dashboard
- [ ] UTM template support
- [ ] Link scheduling & A/B testing
- [ ] Custom domains
- [ ] Team collaboration
- [ ] Mobile app
- [ ] Social sharing

### Phase 3
- [ ] Premium features
- [ ] API documentation
- [ ] Webhooks
- [ ] Bulk operations

## 🤝 Contributing

We love contributions! Please check [CONTRIBUTING.md](CONTRIBUTING.md) to get started.

### Good First Issues
Look for `good first issue` label in [Issues](https://github.com/slowestwind/url-shortner-ts4/issues?q=label%3A"good+first+issue") to find beginner-friendly tasks.

## 📝 License

This project is licensed under the **AGPL v3 License** - see [LICENSE](LICENSE) file for details.

Free forever for everyone. ❤️

## 📞 Support

- 📧 Email: slowestwind@gmail.com
- 🐛 Report bugs: [GitHub Issues](https://github.com/slowestwind/url-shortner-ts4/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/slowestwind/url-shortner-ts4/discussions)

---

**Made with ❤️ in India** 🇮🇳
