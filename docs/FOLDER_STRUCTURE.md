# 📂 Project Folder Structure

**Last Updated:** November 4, 2025

---

## 🌳 Complete Structure

\`\`\`
jobmakerproject/
├── README.md                          ← Main project readme
├── docker-compose.yml                 ← Development Docker config
├── docker-compose.prod.yml            ← Production Docker config
├── Dockerfile                         ← Multi-stage Docker build
├── Makefile                           ← Development commands
├── Makefile.prod                      ← Production commands
├── deploy.sh                          ← Automated deployment script
├── dev.sh                             ← Development helper script
├── .env.example                       ← Environment example
├── env.production.example             ← Production env template
├── .gitignore
├── .cursorignore
│
├── docs/                              📚 ALL DOCUMENTATION HERE
│   ├── README.md                      ← Documentation navigation
│   ├── DOCS_INDEX.md                  ← Master index (82 files)
│   │
│   ├── FINAL_STATUS_COMPLETE.md       ⭐ Complete system status
│   ├── ADMIN_SEEDER_IMPLEMENTATION.md ⭐ Admin user seeder
│   ├── FEATURED_JOBS_SEEDER.md        ⭐ Featured jobs
│   ├── REBRAND_AND_FIX_SUMMARY.md     ⭐ Rebranding summary
│   ├── ARTICLE_VIEW_FIX.md            ⭐ Article fixes
│   │
│   ├── QUICK_START.md                 🚀 Get started in 10 mins
│   ├── INSTALLATION.md                📦 Detailed setup
│   ├── DEVELOPMENT_GUIDE.md           👨‍💻 Development workflow
│   ├── DEPLOYMENT.md                  🚀 Deployment guide
│   ├── CONTRIBUTING.md                🤝 Contributing guide
│   │
│   ├── PRODUCTION_DEPLOYMENT_CHECKLIST.md
│   ├── README_PRODUCTION.md
│   ├── DOCKER_COMMANDS.md
│   └── ... (78 more documentation files)
│
├── docker/
│   ├── frankenphp/
│   │   ├── Caddyfile                  ← Production config (unified)
│   │   └── Caddyfile.dev              ← Development config
│   └── mysql/
│       └── my.cnf
│
└── src/                               🎯 Laravel Application
    ├── app/
    │   ├── Http/
    │   │   ├── Controllers/
    │   │   │   ├── AdminController.php
    │   │   │   ├── ArticleController.php
    │   │   │   ├── EmployerController.php
    │   │   │   ├── JobController.php
    │   │   │   ├── SeekerController.php
    │   │   │   └── ...
    │   │   ├── Middleware/
    │   │   │   ├── AdminMiddleware.php
    │   │   │   ├── CheckActiveUser.php
    │   │   │   ├── EmployerMiddleware.php
    │   │   │   └── SeekerMiddleware.php
    │   │   └── Requests/
    │   │
    │   ├── Models/
    │   │   ├── User.php
    │   │   ├── Job.php
    │   │   ├── Application.php
    │   │   ├── Article.php
    │   │   ├── Category.php
    │   │   ├── Employer.php
    │   │   ├── Seeker.php
    │   │   ├── Message.php
    │   │   ├── Conversation.php
    │   │   └── Setting.php
    │   │
    │   └── Policies/
    │       ├── JobPolicy.php
    │       └── ...
    │
    ├── database/
    │   ├── migrations/
    │   │   ├── 2024_10_14_000001_create_categories_table.php
    │   │   ├── 2024_10_14_000002_create_seekers_table.php
    │   │   ├── 2024_10_14_000003_create_employers_table.php
    │   │   ├── 2024_10_14_000004_create_jobs_table.php
    │   │   ├── 2024_10_14_000005_create_applications_table.php
    │   │   ├── 2025_10_21_021819_create_articles_table.php
    │   │   └── ...
    │   │
    │   └── seeders/
    │       ├── DatabaseSeeder.php            ← Main seeder orchestrator
    │       ├── AdminSeeder.php               ⭐ Runs FIRST
    │       ├── SettingSeeder.php
    │       ├── LocalCategorySeeder.php
    │       ├── LocalSeekerSeeder.php
    │       ├── LocalEmployerSeeder.php
    │       ├── LocalJobSeeder.php
    │       ├── FeaturedJobSeeder.php         ⭐ Marks featured jobs
    │       ├── ApplicationSeeder.php
    │       ├── ConversationSeeder.php
    │       └── LocalArticleSeeder.php
    │
    ├── resources/
    │   ├── views/
    │   │   ├── admin/                        🔒 Admin views
    │   │   │   ├── dashboard.blade.php
    │   │   │   ├── users/
    │   │   │   ├── jobs/
    │   │   │   ├── categories/
    │   │   │   └── messages/
    │   │   │
    │   │   ├── seeker/                       👤 Seeker views
    │   │   │   ├── dashboard.blade.php
    │   │   │   ├── jobs.blade.php
    │   │   │   ├── applications/
    │   │   │   └── messages/
    │   │   │
    │   │   ├── employer/                     🏢 Employer views
    │   │   │   ├── dashboard.blade.php
    │   │   │   ├── jobs/
    │   │   │   ├── applications/
    │   │   │   └── messages/
    │   │   │
    │   │   ├── articles/                     📝 Articles
    │   │   │   ├── index.blade.php
    │   │   │   └── show.blade.php
    │   │   │
    │   │   ├── categories/                   📁 Categories
    │   │   │   └── show.blade.php
    │   │   │
    │   │   ├── jobs/                         💼 Public job listings
    │   │   │   ├── index.blade.php
    │   │   │   └── show.blade.php
    │   │   │
    │   │   ├── employers/                    🏢 Public employer profiles
    │   │   │   └── show.blade.php
    │   │   │
    │   │   ├── resume/                       📄 Public resumes
    │   │   │   └── show.blade.php
    │   │   │
    │   │   ├── pages/                        📄 Static pages
    │   │   │   ├── about.blade.php
    │   │   │   ├── contact.blade.php
    │   │   │   ├── terms.blade.php
    │   │   │   └── faq.blade.php
    │   │   │
    │   │   ├── components/                   🧩 Reusable components
    │   │   │   ├── header.blade.php
    │   │   │   ├── footer.blade.php
    │   │   │   └── ...
    │   │   │
    │   │   ├── layouts/                      📐 Layout templates
    │   │   │   ├── app.blade.php
    │   │   │   ├── admin.blade.php
    │   │   │   └── guest.blade.php
    │   │   │
    │   │   ├── vendor/                       📦 Vendor overrides
    │   │   │   └── pagination/
    │   │   │       └── tailwind.blade.php
    │   │   │
    │   │   └── welcome.blade.php             🏠 Homepage
    │   │
    │   ├── css/
    │   │   └── app.css
    │   │
    │   └── js/
    │       ├── app.js
    │       └── bootstrap.js
    │
    ├── routes/
    │   ├── web.php                           🌐 Web routes
    │   └── api.php                           📡 API routes
    │
    ├── config/                               ⚙️ Configuration
    │   ├── app.php
    │   ├── database.php
    │   ├── cache.php
    │   └── ...
    │
    ├── public/                               🌍 Public assets
    │   ├── index.php
    │   ├── build/
    │   ├── storage/                          (symlink)
    │   └── ...
    │
    ├── storage/
    │   ├── app/
    │   │   ├── private/
    │   │   └── public/
    │   ├── framework/
    │   │   ├── cache/
    │   │   ├── sessions/
    │   │   └── views/
    │   └── logs/
    │
    ├── tests/                                🧪 Tests
    │
    ├── vendor/                               📦 Dependencies
    │
    ├── .env                                  🔧 Environment config
    ├── artisan                               🎨 CLI tool
    ├── composer.json                         📦 PHP dependencies
    ├── package.json                          📦 JS dependencies
    ├── tailwind.config.js                    🎨 Tailwind config
    ├── vite.config.js                        ⚡ Vite config
    └── phpunit.xml                           🧪 PHPUnit config
\`\`\`

---

## 📊 Directory Statistics

\`\`\`
Total Directories: 50+
Total Files:       1,000+
PHP Files:         200+
Blade Files:       150+
JS Files:          10+
Documentation:     82 files
Migrations:        20+
Seeders:           10
\`\`\`

---

## 🎯 Key Directories

### Documentation
\`\`\`
docs/              - All project documentation (82 files)
  ├── Status & Summary docs
  ├── Getting Started guides  
  ├── Development guides
  ├── Production guides
  ├── Feature documentation
  └── Troubleshooting guides
\`\`\`

### Application Source
\`\`\`
src/               - Laravel application root
  ├── app/         - Application logic (Models, Controllers, Policies)
  ├── database/    - Migrations & Seeders
  ├── resources/   - Views, CSS, JS
  ├── routes/      - Route definitions
  └── config/      - Configuration files
\`\`\`

### Docker Configuration
\`\`\`
docker/            - Docker-related configs
  ├── frankenphp/  - FrankenPHP/Caddy configs
  └── mysql/       - MySQL configs
\`\`\`

### Build & Deploy
\`\`\`
Dockerfile                - Multi-stage Docker build
docker-compose.yml        - Development environment
docker-compose.prod.yml   - Production environment
Makefile                  - Dev commands
Makefile.prod            - Prod commands
deploy.sh                - Automated deployment
\`\`\`

---

## 🗂️ Important Files by Purpose

### Entry Points
\`\`\`
README.md                          - Project overview
docs/README.md                     - Documentation entry
docs/DOCS_INDEX.md                 - Master doc index
src/public/index.php               - Application entry
\`\`\`

### Configuration
\`\`\`
.env                               - Local environment
env.production.example             - Production template
docker-compose.yml                 - Dev Docker config
docker-compose.prod.yml            - Prod Docker config
src/config/app.php                 - App configuration
\`\`\`

### Database
\`\`\`
src/database/seeders/DatabaseSeeder.php       - Seeder orchestrator
src/database/seeders/AdminSeeder.php          - Admin user (first)
src/database/seeders/FeaturedJobSeeder.php    - Featured jobs
src/database/migrations/                      - All migrations
\`\`\`

### Documentation (Recent)
\`\`\`
docs/FINAL_STATUS_COMPLETE.md                 - Complete status
docs/ADMIN_SEEDER_IMPLEMENTATION.md           - Admin seeder
docs/FEATURED_JOBS_SEEDER.md                  - Featured jobs
docs/REBRAND_AND_FIX_SUMMARY.md               - Rebranding
docs/ARTICLE_VIEW_FIX.md                      - Article fixes
\`\`\`

---

## 🎨 View Structure

### Public Views
\`\`\`
welcome.blade.php                  - Homepage
jobs/index.blade.php               - Job listings
jobs/show.blade.php                - Job details
articles/index.blade.php           - Article listings
articles/show.blade.php            - Article details
categories/show.blade.php          - Category jobs
employers/show.blade.php           - Employer profile
resume/show.blade.php              - Public resume
pages/*.blade.php                  - Static pages
\`\`\`

### Role-based Dashboards
\`\`\`
admin/dashboard.blade.php          - Admin dashboard
seeker/dashboard.blade.php         - Seeker dashboard
employer/dashboard.blade.php       - Employer dashboard
\`\`\`

### Layouts & Components
\`\`\`
layouts/app.blade.php              - Main layout
layouts/admin.blade.php            - Admin layout
layouts/guest.blade.php            - Guest layout
components/header.blade.php        - Site header
components/footer.blade.php        - Site footer
\`\`\`

---

## 🔧 Configuration Files

### Docker
\`\`\`
Dockerfile                         - Build instructions
docker-compose.yml                 - Dev environment
docker-compose.prod.yml            - Prod environment
docker/frankenphp/Caddyfile        - Production Caddy config
docker/frankenphp/Caddyfile.dev    - Development Caddy config
\`\`\`

### Laravel
\`\`\`
src/.env                           - Environment variables
src/config/app.php                 - App config (name, timezone, etc)
src/config/database.php            - Database connections
src/config/cache.php               - Cache configuration
\`\`\`

### Build Tools
\`\`\`
src/package.json                   - NPM dependencies
src/composer.json                  - PHP dependencies
src/vite.config.js                 - Vite bundler
src/tailwind.config.js             - Tailwind CSS
\`\`\`

---

## 📦 Dependencies

### PHP (Composer)
\`\`\`
laravel/framework: ^12.0           - Laravel core
doctrine/dbal: ^4.0                - Database abstraction
intervention/image: ^3.0           - Image manipulation
spatie/*                           - Various Laravel packages
\`\`\`

### JavaScript (NPM)
\`\`\`
vite: ^5.0                         - Build tool
tailwindcss: ^3.4                  - CSS framework
alpinejs: ^3.14                    - JS framework
axios: ^1.7                        - HTTP client
\`\`\`

---

## 🚀 Quick Navigation

### Start Developing
\`\`\`
1. Read: README.md
2. Setup: docs/QUICK_START.md
3. Develop: docs/DEVELOPMENT_GUIDE.md
4. Commands: Makefile
\`\`\`

### Deploy to Production
\`\`\`
1. Prepare: docs/PRODUCTION_DEPLOYMENT_CHECKLIST.md
2. Deploy: ./deploy.sh
3. Manage: Makefile.prod
4. Monitor: docs/README_PRODUCTION.md
\`\`\`

### Find Documentation
\`\`\`
1. Browse: docs/README.md
2. Search: docs/DOCS_INDEX.md
3. Recent: docs/FINAL_STATUS_COMPLETE.md
\`\`\`

---

**Last Updated:** November 4, 2025  
**Project:** AdoJobs.id  
**Version:** 2.0
