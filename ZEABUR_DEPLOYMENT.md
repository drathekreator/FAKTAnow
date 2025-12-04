# 🚀 Zeabur Deployment Guide - FAKTAnow

## 📋 Prerequisites

1. **Zeabur Account** - Sign up at https://zeabur.com
2. **GitHub Repository** - Push your code to GitHub
3. **Database Ready** - Zeabur will provide MySQL automatically

## 🎯 Quick Deployment Steps

### Step 1: Prepare Your Repository

```bash
# Make sure all changes are committed
git add .
git commit -m "Ready for Zeabur deployment"
git push origin main
```

### Step 2: Create Project on Zeabur

1. Login to Zeabur Dashboard
2. Click "New Project"
3. Connect your GitHub repository
4. Select "faktanow" repository

### Step 3: Add MySQL Service

1. In your project, click "Add Service"
2. Select "MySQL"
3. Wait for MySQL to be provisioned
4. Zeabur will automatically set environment variables:
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_DATABASE`
   - `MYSQL_USERNAME`
   - `MYSQL_PASSWORD`

### Step 4: Configure Environment Variables

In Zeabur Dashboard, go to your service → Environment Variables:

```env
# Required Variables
APP_NAME=FAKTAnow
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:3fUtnmal1CNLbqtNYM4+oPFi09Gqe5vNEYeW+4ExMb0=

# Database (Auto-configured by Zeabur)
DB_CONNECTION=mysql
DB_HOST=${MYSQL_HOST}
DB_PORT=${MYSQL_PORT}
DB_DATABASE=${MYSQL_DATABASE}
DB_USERNAME=${MYSQL_USERNAME}
DB_PASSWORD=${MYSQL_PASSWORD}

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Filesystem
FILESYSTEM_DISK=public

# Locale
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
```

### Step 5: Deploy Application

1. Zeabur will automatically detect Laravel
2. Build process will start automatically
3. Wait for deployment to complete (5-10 minutes)

### Step 6: Run Database Migrations

After deployment, you need to run migrations. Zeabur provides a terminal:

1. Go to your service in Zeabur
2. Click "Terminal" or "Console"
3. Run these commands:

```bash
# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Seed database with categories and default users
php artisan db:seed --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Verify Deployment

1. Open your Zeabur URL (e.g., https://faktanow.zeabur.app)
2. Check homepage loads correctly
3. Test login with default credentials:
   - **Admin:** admin@portalberita.com / password
   - **Editor:** editor@portalberita.com / password
   - **Member:** member@portalberita.com / password

## 🔧 Post-Deployment Configuration

### 1. Update APP_URL

In Zeabur Environment Variables:
```env
APP_URL=https://your-app.zeabur.app
```

### 2. Setup Custom Domain (Optional)

1. Go to your service settings
2. Click "Domains"
3. Add your custom domain
4. Update DNS records as instructed
5. Update `APP_URL` to your custom domain

### 3. Configure File Storage

For production, consider using S3 or similar:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket
```

### 4. Setup Email (Optional)

Configure SMTP for email notifications:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@faktanow.com
MAIL_FROM_NAME=FAKTAnow
```

## 🐛 Troubleshooting

### Issue 1: 500 Internal Server Error

**Solution:**
```bash
# Check logs
php artisan log:clear

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Issue 2: Database Connection Failed

**Solution:**
- Check if MySQL service is running in Zeabur
- Verify environment variables are set correctly
- Make sure `DB_HOST`, `DB_PORT`, etc. are using Zeabur's variables

### Issue 3: Storage/Thumbnails Not Working

**Solution:**
```bash
# Create storage link
php artisan storage:link

# Check permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Issue 4: Assets Not Loading (CSS/JS)

**Solution:**
```bash
# Rebuild assets
npm install
npm run build

# Clear view cache
php artisan view:clear
php artisan view:cache
```

### Issue 5: Session Not Working

**Solution:**
```bash
# Create sessions table if not exists
php artisan session:table
php artisan migrate --force

# Clear session
php artisan cache:clear
```

## 📊 Performance Optimization

### 1. Enable OPcache

Add to your PHP configuration (if accessible):
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 2. Optimize Composer Autoloader

```bash
composer install --optimize-autoloader --no-dev
composer dump-autoload --optimize
```

### 3. Cache Everything

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 4. Use Database for Cache

Already configured in `.env.zeabur`:
```env
CACHE_STORE=database
SESSION_DRIVER=database
```

## 🔐 Security Checklist

- ✅ `APP_DEBUG=false` in production
- ✅ Strong `APP_KEY` generated
- ✅ Database credentials secured
- ✅ HTTPS enabled (Zeabur provides free SSL)
- ✅ CSRF protection enabled (Laravel default)
- ✅ XSS protection enabled (Blade escaping)
- ⚠️ Change default user passwords after deployment
- ⚠️ Setup firewall rules if needed
- ⚠️ Enable rate limiting for API endpoints

## 📈 Monitoring

### Check Application Logs

```bash
# View latest logs
tail -f storage/logs/laravel.log

# Clear old logs
php artisan log:clear
```

### Monitor Database

```bash
# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Check Disk Space

```bash
# Check storage usage
du -sh storage/
du -sh public/storage/
```

## 🔄 Updating Your Application

### Deploy New Changes

1. Push changes to GitHub:
```bash
git add .
git commit -m "Update: description"
git push origin main
```

2. Zeabur will automatically redeploy

3. Run migrations if needed:
```bash
php artisan migrate --force
```

4. Clear cache:
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 💾 Backup Strategy

### Database Backup

```bash
# Export database
mysqldump -h ${MYSQL_HOST} -P ${MYSQL_PORT} -u ${MYSQL_USERNAME} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE} > backup.sql

# Import database
mysql -h ${MYSQL_HOST} -P ${MYSQL_PORT} -u ${MYSQL_USERNAME} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE} < backup.sql
```

### File Backup

```bash
# Backup storage folder
tar -czf storage_backup.tar.gz storage/app/public/thumbnails/

# Restore storage
tar -xzf storage_backup.tar.gz
```

## 🎯 Production Checklist

Before going live:

- [ ] All environment variables configured
- [ ] Database migrated and seeded
- [ ] Storage link created
- [ ] All cache cleared and rebuilt
- [ ] Test all features (login, register, CRUD)
- [ ] Test file uploads (thumbnails)
- [ ] Test search and filters
- [ ] Test admin dashboard
- [ ] Test editor dashboard
- [ ] Change default passwords
- [ ] Setup custom domain (optional)
- [ ] Configure email (optional)
- [ ] Setup monitoring (optional)
- [ ] Create database backup

## 📞 Support

### Zeabur Documentation
- https://zeabur.com/docs

### Laravel Documentation
- https://laravel.com/docs

### FAKTAnow Issues
- Check `TROUBLESHOOTING.md`
- Check `DEPLOYMENT.md`
- Check application logs

---

**Deployment Status:** ✅ READY FOR ZEABUR
**Last Updated:** December 2024
**Version:** 1.3.0
