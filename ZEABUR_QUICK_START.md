# ⚡ Zeabur Quick Start - FAKTAnow

## 🚀 Deploy in 5 Minutes

### Step 1: Push to GitHub (1 min)
```bash
git add .
git commit -m "Ready for Zeabur deployment"
git push origin main
```

### Step 2: Deploy on Zeabur (2 min)
1. Go to https://zeabur.com
2. Click "New Project"
3. Connect GitHub → Select "faktanow" repo
4. Click "Add Service" → Select "MySQL"
5. Wait for deployment (auto-detected as Laravel)

### Step 3: Configure Environment (1 min)
In Zeabur Dashboard → Your Service → Environment Variables:

**Copy these EXACTLY:**
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:3fUtnmal1CNLbqtNYM4+oPFi09Gqe5vNEYeW+4ExMb0=
SESSION_DRIVER=database
CACHE_STORE=database
FILESYSTEM_DISK=public
```

**Database variables are AUTO-SET by Zeabur:**
- DB_CONNECTION=mysql
- DB_HOST=${MYSQL_HOST}
- DB_PORT=${MYSQL_PORT}
- DB_DATABASE=${MYSQL_DATABASE}
- DB_USERNAME=${MYSQL_USERNAME}
- DB_PASSWORD=${MYSQL_PASSWORD}

### Step 4: Run Migrations (1 min)
In Zeabur → Your Service → Terminal:
```bash
php artisan migrate --force
php artisan storage:link
php artisan db:seed --force
php artisan config:cache
```

### Step 5: Test (30 sec)
1. Open your Zeabur URL
2. Login with: **admin@portalberita.com** / **password**
3. ✅ Done!

---

## 🔑 Default Login Credentials

After seeding:
- **Admin:** admin@portalberita.com / password
- **Editor:** editor@portalberita.com / password
- **Member:** member@portalberita.com / password

⚠️ **IMPORTANT:** Change these passwords immediately after first login!

---

## 🐛 Quick Troubleshooting

### Error: 500 Internal Server Error
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### Error: Database Connection Failed
- Check if MySQL service is running in Zeabur
- Verify environment variables are set

### Error: Storage/Thumbnails Not Working
```bash
php artisan storage:link
chmod -R 775 storage
```

### Error: Assets Not Loading
```bash
npm install
npm run build
php artisan view:clear
```

---

## 📞 Need Help?

- Full Guide: `ZEABUR_DEPLOYMENT.md`
- Troubleshooting: `TROUBLESHOOTING.md`
- Zeabur Docs: https://zeabur.com/docs

---

**Status:** ✅ READY FOR DEPLOYMENT
**Estimated Time:** 5 minutes
**Difficulty:** Easy
