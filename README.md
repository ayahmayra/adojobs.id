# JobMaker - Laravel Job Portal System

A modern job portal system built with **Laravel 12**, **Docker**, **FrankenPHP**, **MariaDB**, and **Redis**. This system connects Job Seekers, Employers, and Administrators in a comprehensive platform for job posting and application management.

![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)
![Docker](https://img.shields.io/badge/Docker-Ready-brightgreen.svg)
![License](https://img.shields.io/badge/License-MIT-yellow.svg)

---

## 📖 Documentation

| Document | Description |
|----------|-------------|
| **[📖 Documentation Index](DOCUMENTATION_INDEX.md)** | Complete documentation guide |
| **[⚡ Quick Start](QUICK_START.md)** | Get running in 10 minutes |
| **[📦 Installation Guide](INSTALLATION.md)** | Detailed setup with troubleshooting |
| **[👨‍💻 Development Guide](DEVELOPMENT_GUIDE.md)** | Hot reload, workflow & debugging |
| **[🚀 Deployment Guide](DEPLOYMENT.md)** | Production deployment |
| **[🎨 Welcome Page](WELCOME_PAGE.md)** | Landing page documentation |
| **[🎨 Layout Structure](LAYOUT_STRUCTURE.md)** | Layout system & components guide |
| **[🤝 Contributing](CONTRIBUTING.md)** | How to contribute |

---

## 🎯 Features

### For Job Seekers
- ✅ Create and manage professional profiles
- ✅ Browse and search job listings
- ✅ Filter jobs by category, location, type, and work mode
- ✅ Apply for jobs with cover letters
- ✅ Track application statuses
- ✅ Save jobs for later (TODO)
- ✅ Dashboard with application statistics

### For Employers
- ✅ Create and manage company profiles
- ✅ Post and manage job listings
- ✅ View and manage job applications
- ✅ Update application statuses (pending, reviewed, shortlisted, hired, rejected)
- ✅ Dashboard with job and application metrics
- ✅ Verified company badges

### For Administrators
- ✅ Full system access and control
- ✅ Manage users (seekers, employers)
- ✅ Manage job postings
- ✅ Manage job categories
- ✅ Activate/deactivate user accounts
- ✅ Feature jobs on homepage
- ✅ System statistics and reporting

### Technical Features
- ✅ Role-based access control (Admin, Employer, Seeker)
- ✅ Laravel Breeze authentication
- ✅ Eloquent ORM with proper relationships
- ✅ Database seeding with realistic data
- ✅ Modern, responsive landing page with Tailwind CSS
- ✅ Modular layout system with reusable Blade components
- ✅ Responsive design with Tailwind CSS
- ✅ Redis-based caching and queuing
- ✅ Docker-based development environment
- ✅ FrankenPHP for high performance
- ✅ MariaDB for database
- ✅ Optimized for low-resource environments

---

## 🧱 Technology Stack

| Component | Technology |
|-----------|-----------|
| Framework | Laravel 12 |
| PHP Version | 8.3 |
| Web Server | FrankenPHP |
| Database | MariaDB 11.2 |
| Cache/Queue | Redis 7 |
| Frontend | Blade Templates + Tailwind CSS |
| Container | Docker + Docker Compose |

---

## 📋 Prerequisites

- Docker Desktop (or Docker Engine + Docker Compose)
- Make (optional, for using Makefile commands)
- Git

---

## 🚀 Quick Start

### Method 1: Quick Installation (Recommended)

**Follow the step-by-step guide:**
📖 **[QUICK_START.md](QUICK_START.md)** - Setup dalam 10 menit

### Method 2: Detailed Installation

**For complete installation guide with troubleshooting:**
📖 **[INSTALLATION.md](INSTALLATION.md)** - Dokumentasi lengkap instalasi

### Method 3: One-Liner (Untuk yang sudah familiar)

```bash
# Clone project
git clone <your-repo-url> jobmakerproject && cd jobmakerproject

# Setup environment
cp src/.env.example src/.env

# Build & start containers
docker-compose build app && docker-compose up -d

# Install & setup
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed --force
docker-compose exec app php artisan cache:clear

# Done! Access: http://localhost:8080
```

### Access Points

- **Application:** http://localhost:8080
- **PHPMyAdmin:** http://localhost:8081
  - Server: `db`
  - Username: `jobmaker_user`
  - Password: `jobmaker_password`

---

## 👤 Default Credentials

After seeding, you can log in with these accounts:

### 🔑 Admin Account
```
Email: admin@jobmaker.local
Password: password
Dashboard: http://localhost:8080/admin/dashboard
```

**Capabilities:** Full system access, manage users, jobs, categories, settings

### 🏢 Employer Accounts
```
Email: employer1@jobmaker.local
Password: password
Dashboard: http://localhost:8080/employer/dashboard
```

**Available Employers:**
- `employer1@jobmaker.local` - Tech Innovations Ltd
- `employer2@jobmaker.local` - Green Energy Solutions
- `employer3@jobmaker.local` - Creative Digital Agency

**Capabilities:** Post jobs, manage applications, company profile

### 👤 Job Seeker Accounts
```
Email: seeker1@jobmaker.local
Password: password
Dashboard: http://localhost:8080/seeker/dashboard
```

**Available Seekers:**
- `seeker1@jobmaker.local` - Full Stack Developer
- `seeker2@jobmaker.local` - UI/UX Designer
- `seeker3@jobmaker.local` - Digital Marketing Specialist
- `seeker4@jobmaker.local` - Financial Analyst
- `seeker5@jobmaker.local` - Software Engineer

**Capabilities:** Browse jobs, apply for positions, track applications

**Note:** All passwords are `password` (change in production!)

---

## 🛠️ Makefile Commands

The project includes a Makefile for convenient Docker operations:

```bash
make help              # Show all available commands
make up                # Start all Docker containers
make down              # Stop all Docker containers
make build             # Build Docker images
make rebuild           # Rebuild and restart all containers
make restart           # Restart all containers
make logs              # Show logs from all containers
make logs-app          # Show logs from app container only
make shell             # Access shell in app container
make shell-db          # Access MariaDB shell

# Laravel Commands
make artisan ARGS="..."      # Run artisan command
make composer ARGS="..."     # Run composer command
make migrate                 # Run database migrations
make migrate-fresh           # Fresh migrations (drop all tables)
make seed                    # Seed the database
make fresh                   # Fresh migrations with seeding
make test                    # Run tests

# Optimization
make optimize                # Optimize Laravel for production
make clear                   # Clear all Laravel caches

# Utilities
make clean                   # Clean up Docker resources
make queue                   # Start queue worker
make storage-link            # Create storage symbolic link
```

---

## 📁 Project Structure

```
jobmakerproject/
├── docker/                        # Docker configuration files
│   ├── frankenphp/
│   │   └── Caddyfile             # FrankenPHP server configuration
│   └── mysql/
│       └── my.cnf                # MariaDB configuration
├── src/                          # Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Admin/       # Admin controllers
│   │   │   │   ├── Employer/    # Employer controllers
│   │   │   │   └── Seeker/      # Job seeker controllers
│   │   │   └── Middleware/      # Custom middleware (role-based)
│   │   ├── Models/              # Eloquent models
│   │   └── Policies/            # Authorization policies
│   ├── database/
│   │   ├── migrations/          # Database migrations
│   │   └── seeders/             # Database seeders
│   ├── resources/
│   │   └── views/               # Blade templates
│   └── routes/
│       └── web.php              # Application routes
├── docker-compose.yml            # Docker Compose configuration
├── Dockerfile                    # Docker image definition
├── Makefile                      # Convenient commands
└── README.md                     # This file
```

---

## 🗄️ Database Schema

### Core Tables

1. **users** - User accounts with role-based access (admin, employer, seeker)
2. **seekers** - Job seeker profiles with skills, experience, and education
3. **employers** - Company profiles with verification status
4. **jobs** - Job postings with detailed information
5. **applications** - Job applications with status tracking
6. **categories** - Job categories for organization
7. **saved_jobs** - Saved jobs for seekers (future feature)
8. **settings** - System configuration

### Key Relationships

- `User` → hasOne `Seeker` | `Employer`
- `Employer` → hasMany `Jobs`
- `Job` → belongsTo `Employer`, `Category`
- `Job` → hasMany `Applications`
- `Seeker` → hasMany `Applications`, `SavedJobs`
- `Application` → belongsTo `Job`, `Seeker`

---

## 🔧 Configuration

### Environment Variables

Key environment variables in `src/.env`:

```env
# Application
APP_NAME=JobMaker
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

# Database (Docker)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=jobmaker
DB_USERNAME=jobmaker
DB_PASSWORD=secret

# Redis (Docker)
REDIS_HOST=redis
REDIS_PORT=6379
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Docker Configuration

- **App (FrankenPHP):** Port 8080
- **MariaDB:** Port 3306
- **Redis:** Port 6379
- **PHPMyAdmin:** Port 8081

### Performance Optimization

The system is optimized for low-resource environments:

- PHP OPcache enabled
- Redis caching for sessions and queries
- Database query optimization with indexes
- Optimized MariaDB configuration
- FrankenPHP for better performance

To optimize for production:

```bash
make optimize
```

This will cache:
- Configuration files
- Routes
- Views
- Application bootstrap

---

## 🎨 Customization

### Adding New Job Categories

1. Access admin dashboard
2. Navigate to Categories
3. Add new category with icon and description

### Customizing Email Templates

Email templates are located in `src/resources/views/emails/`

### Modifying Seeders

Edit seeders in `src/database/seeders/` to customize demo data.

---

## 📝 API Routes

### Public Routes
- `GET /` - Homepage
- `GET /jobs` - Browse jobs
- `GET /jobs/{slug}` - Job details

### Admin Routes (Prefix: `/admin`)
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - Manage users
- `GET /admin/jobs` - Manage jobs
- `GET /admin/categories` - Manage categories

### Employer Routes (Prefix: `/employer`)
- `GET /employer/dashboard` - Employer dashboard
- `GET /employer/jobs` - Manage posted jobs
- `GET /employer/applications` - View applications

### Seeker Routes (Prefix: `/seeker`)
- `GET /seeker/dashboard` - Seeker dashboard
- `GET /seeker/applications` - View applications
- `POST /seeker/jobs/{job}/apply` - Apply for job

---

## 🔐 Security Features

- ✅ CSRF protection on all forms
- ✅ Password hashing with bcrypt
- ✅ Role-based middleware protection
- ✅ Active user checking
- ✅ SQL injection prevention via Eloquent ORM
- ✅ XSS protection via Blade templating

---

## 🚧 Future Features (TODO)

The following features are marked for future implementation:

### High Priority
- [ ] Email verification for new users
- [ ] Password reset functionality
- [ ] File upload for CVs and company logos
- [ ] Job bookmarking/saving system
- [ ] Advanced search with filters
- [ ] Email notifications for applications

### Medium Priority
- [ ] Real-time notifications (Laravel Echo + Pusher)
- [ ] Internal messaging system
- [ ] Company reviews and ratings
- [ ] Job recommendation engine
- [ ] Analytics and reporting dashboard
- [ ] Export applications to PDF/Excel

### Low Priority
- [ ] Multi-language support
- [ ] Social media login (OAuth)
- [ ] Video interviews integration
- [ ] AI-powered resume matching
- [ ] Mobile app (API)
- [ ] Payment integration for premium job postings

---

## 🐛 Troubleshooting

### Common Issues & Solutions

#### 1. Docker Daemon Not Running
**Error:** `Cannot connect to the Docker daemon`

**Solution:**
```bash
# macOS: Start Docker Desktop
open -a Docker

# Linux: Start Docker service
sudo systemctl start docker

# Verify
docker ps
```

#### 2. Port Already in Use
**Error:** `Bind for 0.0.0.0:8080 failed: port is already allocated`

**Solution A - Kill process using port:**
```bash
# macOS/Linux
lsof -ti:8080 | xargs kill -9

# Windows PowerShell
Get-Process -Id (Get-NetTCPConnection -LocalPort 8080).OwningProcess | Stop-Process
```

**Solution B - Change port in `docker-compose.yml`:**
```yaml
services:
  app:
    ports:
      - "8090:8080"  # Change 8080 to 8090
```

#### 3. Class "Redis" Not Found
**Error:** `Class "Redis" not found`

**Solution:**
```bash
# Redis extension already in Dockerfile
# Rebuild container:
docker-compose down
docker-compose build --no-cache app
docker-compose up -d

# Verify
docker-compose exec app php -m | grep redis
```

#### 4. Table 'jobs' Already Exists
**Error:** `SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'jobs' already exists`

**Cause:** Laravel's queue table conflicts with our job_postings table

**Solution:** Already fixed! Table renamed to `job_postings`
```bash
# If you still get this error:
docker-compose exec app php artisan migrate:fresh --seed --force
```

#### 5. Container Keeps Restarting
**Error:** `jobmaker_app Restarting`

**Solution:**
```bash
# Check logs
docker-compose logs app --tail=50

# Common causes:
# - Database not ready: Wait 1-2 minutes
# - Caddyfile error: Already fixed in latest version
# - Permission error: Run fix below

# Fix permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

#### 6. Database Connection Refused
**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**
```bash
# Wait for database to fully initialize (30-60 seconds)
docker-compose ps

# Check if db is Up
# Then retry migration
docker-compose exec app php artisan migrate:fresh --seed --force
```

#### 7. Permission Denied Errors
**Error:** `The stream or file "storage/logs/laravel.log" could not be opened`

**Solution:**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

#### 8. 500 Internal Server Error
**Solution:**
```bash
# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Check logs
docker-compose logs app -f
```

### Get More Help

For detailed troubleshooting, see:
- **[INSTALLATION.md](INSTALLATION.md)** - Complete troubleshooting guide
- **Application logs:** `docker-compose logs app -f`
- **Database logs:** `docker-compose logs db -f`

---

## 📊 Performance Tips

### For Development
```bash
# Use hot reload for Vite
docker-compose exec app npm run dev
```

### For Production
```bash
# Optimize application
make optimize

# Use production assets
docker-compose exec app npm run build
```

### Database Optimization
- Use database indexes (already implemented)
- Enable query caching
- Use eager loading for relationships

---

## 🤝 Contributing

This is a demonstration project. If you'd like to extend it:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Development Team

Built as a comprehensive Laravel demonstration project showcasing:
- Modern PHP development practices
- Docker containerization
- Role-based access control
- Eloquent ORM relationships
- RESTful architecture
- Responsive UI/UX design

---

## 📞 Support

For issues, questions, or suggestions:
- Create an issue in the repository
- Check the troubleshooting section above
- Review Laravel documentation: https://laravel.com/docs

---

## 🙏 Acknowledgments

- **Laravel** - The PHP Framework for Web Artisans
- **FrankenPHP** - Modern PHP application server
- **Tailwind CSS** - Utility-first CSS framework
- **Docker** - Containerization platform
- **MariaDB** - Open-source database
- **Redis** - In-memory data structure store

---

**Happy Coding! 🚀**

---

## Quick Reference

```bash
# Start the application
make up

# Stop the application
make down

# View logs
make logs

# Run migrations
make migrate

# Access Laravel shell
make shell

# Run queue worker
make queue

# Clear caches
make clear
```

---

*Last Updated: October 2025*

