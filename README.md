# FAKTAnow - Platform Berita Digital

Platform berita digital yang menyajikan informasi terkini dengan standar jurnalisme berkualitas. Dibangun dengan Laravel 11 dan Tailwind CSS.

## Fitur Utama

### 1. Modern UI/UX
- **Dark Mode Support**: Full dark mode dengan transisi smooth
- **Responsive Design**: Optimal di semua ukuran layar (mobile, tablet, desktop)
- **Auto Layouting**: Grid system yang adaptif untuk konten
- **Logo Integration**: Logo FAKTAnow di navbar dan footer
- **Functional Searchbar**: Pencarian real-time dengan UI modern
- **Category Navigation**: Filter kategori dengan horizontal scroll

### 2. Sistem Multi-Role
- **Admin**: Kelola pengguna, artikel, dan moderasi komentar
- **Editor**: Buat, edit, dan kelola artikel sendiri
- **User**: Baca artikel dan berkomentar

### 3. Manajemen Artikel
- CRUD artikel lengkap dengan thumbnail
- Kategori artikel (Politik, Ekonomi, Teknologi, dll)
- Status artikel (Draft, Published, Pending, Rejected)
- Slug otomatis untuk SEO-friendly URL
- View counter untuk tracking popularitas

### 4. Sistem Komentar
- User dapat berkomentar pada artikel
- Moderasi komentar oleh admin
- Approval system untuk komentar

### 5. Fitur Pencarian
- Pencarian artikel berdasarkan judul dan konten
- Filter artikel berdasarkan kategori

### 6. Dashboard
- Dashboard Admin: Kelola user, artikel, dan komentar
- Dashboard Editor: Kelola artikel pribadi
- Statistik dan laporan

## Teknologi yang Digunakan

- **Framework**: Laravel 11
- **Frontend**: Tailwind CSS, Blade Templates
- **Database**: SQLite (dapat diganti dengan MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze
- **Asset Bundling**: Vite

## Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### Langkah Instalasi

1. Clone repository
```bash
git clone <repository-url>
cd FAKTAnow
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`
```env
DB_CONNECTION=sqlite
# atau untuk MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=faktanow
# DB_USERNAME=root
# DB_PASSWORD=
```

5. Jalankan migrasi dan seeder
```bash
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

**Note**: Migration akan membuat tabel `likes` untuk fitur like artikel.

6. Buat storage link
```bash
php artisan storage:link
```

7. Build assets
```bash
npm run build
# atau untuk development:
npm run dev
```

8. Jalankan server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Struktur Database

### Users
- id, name, email, password, role (admin/editor/user)

### Articles
- id, user_id, category_id, title, slug, content, thumbnail_url, views, status

### Categories
- id, name, slug

### Comments
- id, user_id, article_id, content, is_approved (always true)

### Likes
- id, user_id, article_id (unique constraint)

## Akun Default

Setelah instalasi, buat akun admin pertama melalui register, lalu ubah role di database:

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
```

## Fitur yang Sudah Diimplementasi

✅ Authentication & Authorization (Multi-role)
✅ CRUD Artikel dengan Upload Thumbnail
✅ Kategori Artikel
✅ Sistem Komentar Instant (tanpa moderasi)
✅ Sistem Like Artikel
✅ Pencarian Artikel
✅ Dashboard Admin & Editor
✅ View Counter
✅ Status Artikel (Draft/Published/Pending/Rejected)
✅ Responsive Design

## Route Utama

### Public Routes
- `/` - Homepage dengan daftar artikel
- `/article/{slug}` - Detail artikel
- `/category/{slug}` - Artikel berdasarkan kategori
- `/login` - Login
- `/register` - Register

### Admin Routes (role: admin)
- `/admin/dashboard` - Dashboard admin
- `/admin/comments/moderate` - Moderasi komentar
- `/admin/articles/review` - Review artikel

### Editor Routes (role: editor)
- `/editor/dashboard` - Dashboard editor
- `/editor/articles/create` - Buat artikel baru
- `/editor/articles/{slug}/edit` - Edit artikel

## Kontribusi

Kontribusi selalu diterima! Silakan buat pull request atau laporkan issue.

## Lisensi

Project ini menggunakan lisensi MIT.

## Tim Pengembang

Kelompok 5 - Pemrograman Web
- [Nama Anggota 1]
- [Nama Anggota 2]
- [Nama Anggota 3]
- [Nama Anggota 4]
- [Nama Anggota 5]

---

© 2025 FAKTAnow. All rights reserved.
