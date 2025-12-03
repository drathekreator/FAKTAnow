# Changelog - FAKTAnow

## [1.2.0] - 2025-12-03

### ✨ New Features
- **Like System**: Users can now like articles
  - Like/Unlike toggle functionality
  - Like count display on homepage and detail page
  - Heart icon with filled state for liked articles
- **Instant Comments**: Comments are now published instantly without admin approval
  - Removed comment moderation system
  - All authenticated users can comment freely
  - Comments appear immediately after submission

### 🔧 Bug Fixes
- **Thumbnail Path**: Fixed thumbnail display using correct storage path
  - Removed unnecessary `asset()` wrapper
  - Added fallback image with `onerror` handler
  - Thumbnails now load correctly from `/storage/thumbnails/`

### 🗑️ Removed Features
- Comment moderation system (admin approval no longer required)
- Admin comment moderation dashboard
- Comment approval routes and controllers

### 📊 Database Changes
- Added `likes` table with user_id and article_id
- Unique constraint to prevent duplicate likes
- Cascade delete on user and article deletion

## [1.1.0] - 2025-12-03

### ✨ Major UI/UX Improvements
- **Dark Mode Support**: Implemented full dark mode support across all views using Tailwind CSS
- **Homepage Redesign**: Complete redesign with auto-layouting, responsive grid, and better card design
- **Logo Integration**: Added FAKTAnow logo to navbar and footer
- **Functional Searchbar**: Improved search functionality with better UI
- **Category Navigation**: Enhanced category filter with horizontal scrolling
- **Responsive Design**: Improved mobile and tablet responsiveness

### 🎨 Design Updates
- **Homepage**: 
  - New navbar with logo, searchbar, and category navigation
  - Improved article cards with hover effects
  - Better thumbnail display with fallback gradient
  - Enhanced footer with logo and better layout
- **Detail Page**:
  - Redesigned article header with breadcrumb
  - Better comment section with user avatars
  - Improved typography and spacing
- **Admin Dashboard**:
  - Modern card-based layout
  - Better table design with hover states
  - Improved role selector with color coding
- **Editor Dashboard**:
  - Clean stats cards
  - Better article list table
  - Improved empty states
- **Forms (Create/Edit)**:
  - Modern form design with better labels
  - File upload with drag-and-drop UI
  - Better error message display
  - Thumbnail preview in edit form

### 🌙 Dark Mode Features
- Automatic color scheme adaptation
- Smooth transitions between light/dark modes
- Proper contrast ratios for accessibility
- Dark mode support for all components:
  - Navbars and footers
  - Cards and tables
  - Forms and inputs
  - Buttons and links
  - Modals and dropdowns

### 🔧 Technical Improvements
- Removed inline CSS in favor of Tailwind classes
- Better component organization
- Improved accessibility with ARIA labels
- Optimized image loading
- Better error handling in views

## [1.0.0] - 2025-12-03

### ✅ Fixed
- **SearchController**: Memperbaiki error variabel `$news` menjadi `$articles`
- **SearchController**: Memperbaiki operator SQL dari `likes` menjadi `like`
- **Homepage**: Menambahkan route controller untuk homepage (sebelumnya hanya return view)
- **Homepage**: Memperbaiki link "Baca Selengkapnya" yang mengarah ke detail artikel
- **Homepage**: Menampilkan thumbnail artikel dari database
- **Homepage**: Menampilkan kategori artikel yang benar
- **Admin Dashboard**: Memperbaiki typo `hrelf` menjadi `href` pada link edit artikel
- **Admin Dashboard**: Memperbaiki route delete artikel menggunakan `admin.articles.destroy`
- **Admin Dashboard**: Menghapus form edit role yang duplikat
- **Admin Dashboard**: Menampilkan status artikel yang benar
- **AdminController**: Memperbaiki validasi role dari `admin, editor, member` menjadi `admin,editor,user`
- **Editor Dashboard**: Menghapus duplikasi HTML
- **Middleware**: Mendaftarkan middleware `role` di bootstrap/app.php

### ✨ Added
- **Detail Article Page**: Implementasi lengkap halaman detail artikel dengan data dinamis
- **Comment System**: Controller untuk menangani komentar (store, approve, destroy, moderate)
- **Comment Routes**: Route untuk submit komentar dan moderasi admin
- **Comment View**: Tampilan komentar di halaman detail artikel
- **Comment Moderation**: Halaman moderasi komentar untuk admin
- **Article Review**: Halaman review artikel untuk admin dengan update status
- **Category Seeder**: Seeder untuk mengisi data kategori default
- **Category Field**: Menambahkan field kategori di form create dan edit artikel
- **Category Validation**: Validasi category_id di ArticleController
- **View Counter**: Increment views saat artikel dibuka
- **Breadcrumb**: Navigasi breadcrumb di halaman detail artikel
- **README.md**: Dokumentasi lengkap instalasi dan fitur
- **CHANGELOG.md**: File changelog untuk tracking perubahan

### 🔧 Improved
- **Homepage Controller**: Menambahkan error handling yang lebih baik
- **Detail Page**: Menambahkan navbar dan footer yang konsisten
- **Admin Dashboard**: Menambahkan link ke moderasi komentar dan review artikel
- **Editor Dashboard**: Tampilan yang lebih bersih dan terstruktur
- **Routes**: Organisasi route yang lebih baik dengan grouping

### 📝 Features Completed
1. ✅ Multi-role authentication (Admin, Editor, User)
2. ✅ CRUD Artikel dengan thumbnail upload
3. ✅ Kategori artikel
4. ✅ Sistem komentar dengan moderasi
5. ✅ Pencarian artikel
6. ✅ Dashboard Admin & Editor
7. ✅ View counter
8. ✅ Status artikel (Draft/Published/Pending/Rejected)
9. ✅ Detail artikel dengan komentar
10. ✅ Filter artikel berdasarkan kategori

### 🐛 Bug Fixes
- Fixed undefined variable `$categories` di homepage
- Fixed article detail page tidak ada controller
- Fixed comment system tidak terimplementasi
- Fixed search functionality error
- Fixed admin dashboard link errors
- Fixed editor dashboard HTML duplication
- Fixed middleware not registered

### 🔐 Security
- Validasi input untuk semua form
- Authorization check untuk edit/delete artikel
- Comment moderation system
- Role-based access control

---

## Catatan Upgrade

Untuk mengupdate dari versi sebelumnya:

1. Jalankan migrasi jika ada perubahan database:
```bash
php artisan migrate
```

2. Jalankan seeder kategori:
```bash
php artisan db:seed --class=CategorySeeder
```

3. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

4. Rebuild assets:
```bash
npm run build
```
