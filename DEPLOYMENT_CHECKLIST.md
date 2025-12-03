# Deployment Checklist - FAKTAnow v1.1.0

## Pre-Deployment

### 1. Environment Setup
- [ ] Copy `.env.example` to `.env`
- [ ] Set `APP_NAME=FAKTAnow`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate `APP_KEY` dengan `php artisan key:generate`
- [ ] Configure database credentials
- [ ] Set proper `APP_URL`

### 2. Database
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed categories: `php artisan db:seed --class=CategorySeeder`
- [ ] Create admin user dan set role ke 'admin'

### 3. Storage & Assets
- [ ] Create storage link: `php artisan storage:link`
- [ ] Set proper permissions:
  - `chmod -R 755 storage`
  - `chmod -R 755 bootstrap/cache`
- [ ] Build production assets: `npm run build`

### 4. Optimization
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`

## Testing Checklist

### Homepage
- [ ] Logo tampil dengan benar
- [ ] Searchbar berfungsi
- [ ] Category navigation berfungsi
- [ ] Article cards tampil dengan proper layout
- [ ] Pagination berfungsi
- [ ] Dark mode toggle works (jika ada)
- [ ] Responsive di mobile, tablet, desktop

### Article Detail
- [ ] Breadcrumb navigation berfungsi
- [ ] Article content tampil lengkap
- [ ] Thumbnail tampil atau fallback gradient
- [ ] Comment form berfungsi (untuk authenticated users)
- [ ] Comment list tampil
- [ ] View counter increment
- [ ] Dark mode styling correct

### Admin Dashboard
- [ ] Stats cards tampil dengan data correct
- [ ] User management table berfungsi
- [ ] Role selector update berfungsi
- [ ] Delete user berfungsi
- [ ] Article management table berfungsi
- [ ] Edit/Delete article berfungsi
- [ ] Dark mode styling correct

### Editor Dashboard
- [ ] Stats cards tampil
- [ ] Article list tampil
- [ ] Create article button berfungsi
- [ ] Edit article berfungsi
- [ ] Delete article berfungsi
- [ ] Status indicator correct
- [ ] Dark mode styling correct

### Create/Edit Article
- [ ] Form validation berfungsi
- [ ] Category dropdown populated
- [ ] Thumbnail upload berfungsi
- [ ] Slug auto-generate berfungsi
- [ ] Submit berfungsi
- [ ] Error messages tampil dengan benar
- [ ] Dark mode styling correct

### Comment Moderation
- [ ] Pending comments list tampil
- [ ] Approve button berfungsi
- [ ] Delete button berfungsi
- [ ] Article link berfungsi
- [ ] Dark mode styling correct

### Article Review
- [ ] Articles list tampil
- [ ] Status dropdown berfungsi
- [ ] Status update berfungsi
- [ ] View article link berfungsi
- [ ] Dark mode styling correct

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong database password
- [ ] `.env` file not accessible via web
- [ ] CSRF protection enabled
- [ ] SQL injection protection (Eloquent ORM)
- [ ] XSS protection (Blade escaping)
- [ ] File upload validation
- [ ] Role-based access control working

## Performance Checklist

- [ ] Images optimized
- [ ] CSS/JS minified (via Vite build)
- [ ] Gzip compression enabled
- [ ] Browser caching configured
- [ ] Database queries optimized (eager loading)
- [ ] No N+1 query problems

## Browser Compatibility

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile browsers (iOS Safari, Chrome Mobile)

## Dark Mode Checklist

- [ ] All text readable in dark mode
- [ ] All backgrounds have proper contrast
- [ ] All borders visible in dark mode
- [ ] All buttons styled correctly
- [ ] All forms styled correctly
- [ ] All tables styled correctly
- [ ] All cards styled correctly
- [ ] All modals/dropdowns styled correctly

## Final Checks

- [ ] All links working
- [ ] No console errors
- [ ] No PHP errors in logs
- [ ] Proper error pages (404, 500)
- [ ] Favicon present
- [ ] Meta tags configured
- [ ] SSL certificate installed (HTTPS)
- [ ] Backup system configured

## Post-Deployment

- [ ] Test all critical user flows
- [ ] Monitor error logs
- [ ] Check performance metrics
- [ ] Verify email functionality (if applicable)
- [ ] Test on real devices
- [ ] Get user feedback

## Rollback Plan

If issues occur:
1. Revert to previous version
2. Restore database backup
3. Clear all caches
4. Check error logs
5. Fix issues in staging
6. Re-deploy

---

**Version**: 1.1.0  
**Date**: December 3, 2025  
**Deployment Status**: ✅ Ready for Production
