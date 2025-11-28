# Technology Stack - AdoJobs.id

Dokumentasi lengkap tentang stack teknologi yang digunakan pada sistem AdoJobs.id.

---

## 🎯 Overview

AdoJobs.id adalah platform lowongan kerja lokal yang dibangun dengan teknologi modern dan optimized untuk performa tinggi dengan resource minimal.

---

## 🧱 Core Technology Stack

### Backend Framework

| Component | Version | Purpose |
|-----------|---------|---------|
| **Laravel** | 12.x | PHP Framework utama |
| **PHP** | 8.3 | Programming language |
| **Composer** | Latest | PHP dependency manager |

**Key Laravel Packages:**
- `laravel/framework: ^12.0` - Core framework
- `laravel/tinker: ^2.10.1` - REPL untuk debugging
- `laravel/breeze: ^2.3` - Authentication scaffolding

---

### Web Server & Runtime

| Component | Version | Purpose |
|-----------|---------|---------|
| **FrankenPHP** | Latest (PHP 8.3) | Modern PHP application server |
| **Caddy** | Built-in | HTTP server (via FrankenPHP) |

**Why FrankenPHP?**
- ✅ Built-in HTTP/2 & HTTP/3 support
- ✅ Automatic HTTPS dengan Let's Encrypt
- ✅ Worker mode untuk performa tinggi
- ✅ Lower memory footprint vs Nginx+PHP-FPM
- ✅ Zero configuration untuk development

**Alternative untuk Production (Native):**
- Nginx + PHP-FPM 8.2
- Supervisor untuk queue workers

---

### Database

| Component | Version | Purpose |
|-----------|---------|---------|
| **MariaDB** | 11.2 | Primary database |
| **PHPMyAdmin** | Latest | Database management GUI |

**Database Configuration:**
- Character Set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`
- Optimized for low-resource environments
- Custom `my.cnf` untuk performance tuning

**Key Tables:**
- `users` - User accounts (admin, employer, seeker)
- `seekers` - Job seeker profiles
- `employers` - Company profiles
- `job_postings` - Job listings
- `applications` - Job applications
- `categories` - Job categories
- `settings` - System configuration

---

### Cache & Queue

| Component | Version | Purpose |
|-----------|---------|---------|
| **Redis** | 7.x | Cache, session, queue |

**Redis Usage:**
- `CACHE_DRIVER=redis` - Application cache
- `SESSION_DRIVER=redis` - User sessions
- `QUEUE_CONNECTION=redis` - Background jobs

**Benefits:**
- ✅ Fast in-memory caching
- ✅ Persistent sessions
- ✅ Reliable queue system
- ✅ Reduced database load

---

## 🎨 Frontend Stack

### UI Framework & Styling

| Component | Version | Purpose |
|-----------|---------|---------|
| **Blade Templates** | Laravel 12 | Server-side templating |
| **Tailwind CSS** | ^3.1.0 | Utility-first CSS framework |
| **Alpine.js** | ^3.4.2 | Lightweight JavaScript framework |
| **Vite** | ^7.0.7 | Frontend build tool |

**Tailwind Plugins:**
- `@tailwindcss/forms: ^0.5.2` - Form styling
- `@tailwindcss/vite: ^4.0.0` - Vite integration

**Additional Libraries:**
- `chart.js: ^4.4.1` - Data visualization
- `axios: ^1.11.0` - HTTP client
- `autoprefixer: ^10.4.2` - CSS vendor prefixes
- `postcss: ^8.4.31` - CSS processing

---

## 🐳 Containerization & DevOps

### Docker Stack

| Component | Version | Purpose |
|-----------|---------|---------|
| **Docker** | Latest | Containerization platform |
| **Docker Compose** | v2 | Multi-container orchestration |

**Docker Services:**

```yaml
services:
  app:        # FrankenPHP + Laravel
  db:         # MariaDB
  redis:      # Redis cache/queue
  phpmyadmin: # Database GUI
```

**Container Images:**
- `dunglas/frankenphp:latest-php8.3` - App container
- `mariadb:11.2` - Database
- `redis:7-alpine` - Cache/Queue
- `phpmyadmin/phpmyadmin:latest` - DB Management

---

## 🔧 Development Tools

### PHP Development

| Tool | Version | Purpose |
|------|---------|---------|
| **Laravel Pint** | ^1.24 | Code style fixer |
| **PHPUnit** | ^11.5.3 | Testing framework |
| **Laravel Pail** | ^1.2.2 | Log viewer |
| **Laravel Sail** | ^1.41 | Docker development environment |
| **Mockery** | ^1.6 | Mocking framework |
| **Collision** | ^8.6 | Error handler |
| **Faker** | ^1.23 | Fake data generator |

### Frontend Development

| Tool | Version | Purpose |
|------|---------|---------|
| **Vite** | ^7.0.7 | Build tool & dev server |
| **Laravel Vite Plugin** | ^2.0.0 | Laravel integration |
| **Concurrently** | ^9.0.1 | Run multiple commands |
| **PostCSS** | ^8.4.31 | CSS processing |

---

## 📦 PHP Extensions

Extensions yang diinstall di container:

### Core Extensions
- `pdo` - Database abstraction
- `pdo_mysql` - MySQL/MariaDB driver
- `mbstring` - Multibyte string handling
- `exif` - Image metadata
- `pcntl` - Process control
- `bcmath` - Arbitrary precision math
- `gd` - Image processing
- `zip` - Archive handling
- `intl` - Internationalization
- `opcache` - PHP opcode cache

### PECL Extensions
- `redis` - Redis client

### OPcache Configuration
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

---

## 🚀 Performance Optimizations

### PHP Configuration

```ini
memory_limit=256M
upload_max_filesize=20M
post_max_size=20M
max_execution_time=300
```

### Laravel Optimizations

**Production Caching:**
```bash
php artisan config:cache    # Cache configuration
php artisan route:cache     # Cache routes
php artisan view:cache      # Cache Blade views
composer dump-autoload --optimize
```

**Database Optimizations:**
- Indexed columns untuk search
- Eager loading untuk relationships
- Query optimization
- Redis caching untuk frequent queries

---

## 🏗️ Architecture Patterns

### Design Patterns

- **MVC (Model-View-Controller)** - Laravel default
- **Repository Pattern** - Data access abstraction
- **Service Layer** - Business logic separation
- **Policy-based Authorization** - Access control
- **Observer Pattern** - Event handling
- **Factory Pattern** - Object creation (seeders)

### Code Organization

```
src/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/      # Admin controllers
│   │   │   ├── Employer/   # Employer controllers
│   │   │   └── Seeker/     # Seeker controllers
│   │   └── Middleware/     # Custom middleware
│   ├── Models/             # Eloquent models
│   ├── Policies/           # Authorization policies
│   └── helpers.php         # Helper functions
├── database/
│   ├── migrations/         # Database schema
│   └── seeders/           # Sample data
├── resources/
│   └── views/             # Blade templates
└── routes/
    └── web.php            # Application routes
```

---

## 🔐 Security Stack

### Authentication & Authorization

- **Laravel Breeze** - Authentication scaffolding
- **Bcrypt** - Password hashing
- **CSRF Protection** - Form security
- **Middleware** - Route protection
- **Policies** - Model-level authorization

### Security Features

- ✅ Role-based access control (Admin, Employer, Seeker)
- ✅ Active user checking
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade templating)
- ✅ HTTPS enforcement (production)
- ✅ Secure session handling (Redis)

---

## 📊 Monitoring & Logging

### Logging

- **Laravel Log** - Application logs
- **Docker Logs** - Container logs
- **Laravel Pail** - Real-time log viewer

### Health Checks

```dockerfile
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s \
    CMD curl -f http://localhost:8080/ || exit 1
```

---

## 🌐 Deployment Options

### Option 1: Docker (Recommended)

**Stack:**
- Docker + Docker Compose
- FrankenPHP
- MariaDB container
- Redis container

**Pros:**
- ✅ Consistent environment
- ✅ Easy scaling
- ✅ Isolated dependencies
- ✅ Quick deployment

### Option 2: Native (Traditional)

**Stack:**
- Nginx + PHP-FPM 8.2
- MariaDB (native)
- Redis (native)
- Supervisor (queue workers)

**Pros:**
- ✅ Lower overhead
- ✅ Direct system access
- ✅ Traditional hosting compatible

---

## 📈 Scalability Considerations

### Horizontal Scaling

- Load balancer (Nginx/HAProxy)
- Multiple app instances
- Shared Redis for sessions
- Centralized database

### Vertical Scaling

- Increase PHP-FPM workers
- Optimize OPcache settings
- Database query optimization
- Redis memory allocation

### Queue Workers

```bash
# Supervisor configuration
numprocs=2  # Multiple workers
```

---

## 🔄 CI/CD Potential

### Recommended Tools

- **GitHub Actions** - Automated testing & deployment
- **GitLab CI** - Alternative CI/CD
- **Docker Hub** - Container registry
- **Deployer** - PHP deployment tool

### Deployment Workflow

```bash
1. Git push to main
2. Run tests (PHPUnit)
3. Build Docker image
4. Push to registry
5. Deploy to production
6. Run migrations
7. Clear caches
```

---

## 📚 Documentation & Resources

### Official Documentation

- [Laravel 12](https://laravel.com/docs/12.x)
- [FrankenPHP](https://frankenphp.dev/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [MariaDB](https://mariadb.org/documentation/)
- [Redis](https://redis.io/documentation)

### Project Documentation

- `README.md` - Project overview
- `docs/` - Detailed documentation
- `SERVER_SETUP_FRESH.md` - Fresh server setup
- `TECH_STACK.md` - This file

---

## 🎯 System Requirements

### Development

- **Docker Desktop** or Docker Engine + Compose
- **Git**
- **Make** (optional, untuk Makefile)
- **4GB RAM** minimum
- **10GB** disk space

### Production (Docker)

- **Ubuntu 20.04/22.04/24.04**
- **4GB RAM** minimum (8GB recommended)
- **20GB** disk space
- **Docker** + **Docker Compose**

### Production (Native)

- **Ubuntu 20.04/22.04/24.04**
- **PHP 8.2+** dengan extensions
- **Nginx** atau **Apache**
- **MariaDB 10.5+**
- **Redis 6+**
- **Composer**
- **Node.js 20.x** (untuk build assets)
- **Supervisor** (untuk queue workers)

---

## 🔮 Future Technology Considerations

### Potential Upgrades

- [ ] **Laravel Octane** - Extreme performance boost
- [ ] **PostgreSQL** - Advanced database features
- [ ] **Elasticsearch** - Full-text search
- [ ] **Laravel Horizon** - Queue monitoring
- [ ] **Laravel Echo + Pusher** - Real-time features
- [ ] **Meilisearch** - Fast search engine
- [ ] **MinIO** - Object storage for files
- [ ] **Prometheus + Grafana** - Monitoring

### API Development

- [ ] **Laravel Sanctum** - API authentication
- [ ] **Laravel Passport** - OAuth2 server
- [ ] **API Resources** - JSON transformation
- [ ] **Swagger/OpenAPI** - API documentation

---

## 📊 Performance Benchmarks

### Expected Performance (8GB RAM Server)

- **Response Time:** < 100ms (cached)
- **Concurrent Users:** 100-500
- **Database Queries:** < 50ms average
- **Memory Usage:** ~512MB (app)
- **CPU Usage:** < 30% (normal load)

### Optimization Targets

- **Time to First Byte (TTFB):** < 200ms
- **Page Load Time:** < 2s
- **API Response:** < 100ms
- **Database Queries:** < 10 per request

---

## 🛠️ Maintenance Tools

### Included Scripts

```bash
# Deployment
deploy.sh                    # Deploy to production
deploy-production.sh         # Production deployment
deploy-with-env.sh          # Deploy with env setup

# Debugging
debug-500-error.sh          # Debug 500 errors
check-app-health.sh         # Health check
check-database-status.sh    # Database status
check-laravel-error.sh      # Laravel errors
troubleshoot-deployment.sh  # Deployment issues

# Fixes
fix-storage-permissions.sh  # Storage permissions
fix-database-production.sh  # Database issues
fix-cloudflare-cache.sh     # Cloudflare cache
```

### Makefile Commands

```bash
make up                     # Start containers
make down                   # Stop containers
make shell                  # Access container shell
make logs                   # View logs
make migrate                # Run migrations
make fresh                  # Fresh database
make optimize               # Optimize for production
make clear                  # Clear all caches
```

---

## 📝 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-11 | Initial release with Laravel 12 |

---

## 🤝 Stack Selection Rationale

### Why Laravel 12?
- Latest stable version
- Modern PHP 8.3 features
- Excellent documentation
- Large ecosystem
- Active community

### Why FrankenPHP?
- Better performance than traditional PHP-FPM
- Built-in HTTP/2 & HTTP/3
- Lower memory footprint
- Easy HTTPS setup
- Modern architecture

### Why MariaDB?
- Drop-in MySQL replacement
- Better performance
- Open-source
- Active development
- Compatible with existing tools

### Why Redis?
- Fast in-memory caching
- Reliable queue system
- Session storage
- Pub/Sub capabilities
- Low resource usage

### Why Tailwind CSS?
- Utility-first approach
- Small production bundle
- Highly customizable
- Great developer experience
- Excellent documentation

---

**Last Updated:** 2025-11-27  
**Maintained By:** AdoJobs.id Development Team
