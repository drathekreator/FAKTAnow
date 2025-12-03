# Deployment Guide - FAKTAnow

## 📋 Pre-Deployment Checklist

- [ ] Semua fitur sudah ditest di local
- [ ] Database sudah di-backup
- [ ] Environment variables sudah disiapkan
- [ ] SSL certificate sudah disiapkan (untuk production)
- [ ] Domain sudah disiapkan
- [ ] Server requirements terpenuhi

## 🖥️ Server Requirements

### Minimum Requirements
- PHP >= 8.2
- MySQL >= 5.7 atau PostgreSQL >= 10
- Composer
- Node.js >= 18.x
- NPM atau Yarn
- Web Server (Apache/Nginx)

### Recommended Requirements
- PHP 8.3
- MySQL 8.0 atau PostgreSQL 15
- 2GB RAM minimum
- 10GB Storage
- SSL Certificate

## 🚀 Deployment ke Shared Hosting

### 1. Persiapan File

```bash
# Build production assets
npm run build

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Upload Files

Upload semua file KECUALI:
- `node_modules/`
- `.env` (buat baru di server)
- `storage/` (upload struktur folder saja)
- `.git/`

### 3. Setup di Server

```bash
# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create .env file
cp .env.example .env
nano .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed categories
php artisan db:seed --class=CategorySeeder

# Create storage link
php artisan storage:link
```

### 4. Configure .htaccess

Pastikan file `public/.htaccess` ada dan berisi:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## 🐳 Deployment dengan Docker

### 1. Create Dockerfile

```dockerfile
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
```

### 2. Create docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: faktanow-app
    volumes:
      - .:/var/www
    networks:
      - faktanow

  nginx:
    image: nginx:alpine
    container_name: faktanow-nginx
    ports:
      - "80:80"
    volumes:
      - .:/var/www
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    networks:
      - faktanow

  db:
    image: mysql:8.0
    container_name: faktanow-db
    environment:
      MYSQL_DATABASE: faktanow
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - faktanow

networks:
  faktanow:
    driver: bridge

volumes:
  dbdata:
```

### 3. Deploy

```bash
docker-compose up -d
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --class=CategorySeeder
```

## ☁️ Deployment ke VPS (Ubuntu)

### 1. Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Nginx
sudo apt install nginx -y

# Install MySQL
sudo apt install mysql-server -y
```

### 2. Setup Database

```bash
sudo mysql
```

```sql
CREATE DATABASE faktanow;
CREATE USER 'faktanow'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON faktanow.* TO 'faktanow'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Clone & Setup Application

```bash
cd /var/www
sudo git clone <your-repo-url> faktanow
cd faktanow

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
nano .env  # Edit database credentials

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force
php artisan db:seed --class=CategorySeeder

# Set permissions
sudo chown -R www-data:www-data /var/www/faktanow
sudo chmod -R 755 /var/www/faktanow/storage
sudo chmod -R 755 /var/www/faktanow/bootstrap/cache

# Create storage link
php artisan storage:link
```

### 4. Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/faktanow
```

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/faktanow/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/faktanow /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 5. Setup SSL (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d your-domain.com
```

## 🔄 Update/Redeploy

```bash
cd /var/www/faktanow

# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear & cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

## 📊 Monitoring & Maintenance

### Setup Cron Jobs

```bash
crontab -e
```

Add:
```
* * * * * cd /var/www/faktanow && php artisan schedule:run >> /dev/null 2>&1
```

### Setup Log Rotation

```bash
sudo nano /etc/logrotate.d/faktanow
```

```
/var/www/faktanow/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
}
```

### Backup Script

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/faktanow"

# Backup database
mysqldump -u faktanow -p faktanow > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/faktanow

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

## 🔒 Security Checklist

- [ ] SSL Certificate installed
- [ ] `.env` file secured (not accessible via web)
- [ ] Debug mode OFF (`APP_DEBUG=false`)
- [ ] Strong database password
- [ ] Firewall configured
- [ ] Regular backups scheduled
- [ ] File permissions correct (755 for directories, 644 for files)
- [ ] PHP version up to date
- [ ] Dependencies up to date

## 🐛 Troubleshooting Production

### Check Logs
```bash
tail -f /var/www/faktanow/storage/logs/laravel.log
tail -f /var/log/nginx/error.log
```

### Clear Cache
```bash
php artisan optimize:clear
```

### Fix Permissions
```bash
sudo chown -R www-data:www-data /var/www/faktanow
sudo chmod -R 755 /var/www/faktanow/storage
```

---

**Note**: Selalu test deployment di staging environment sebelum production!
