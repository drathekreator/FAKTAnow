# FAKTAnow

FAKTAnow adalah web portal berita digital berbasis Laravel 11 dan Tailwind CSS dengan arsitektur multi-role (Admin, Editor, User), manajemen artikel modular, dan sistem interaksi pembaca real-time.

---

## ⚡ Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Components, Tailwind CSS, Alpine.js / Vite
- **Database**: MySQL / PostgreSQL / SQLite
- **Auth**: Laravel Breeze (Multi-guard & role middleware)
- **Deployment**: Docker / Zeabur / VPS ready

---

## 🚀 Fitur Utama

- **Role-Based Access Control (RBAC)**:
  - `Admin`: Kelola user, moderasi konten, dan review publikasi.
  - `Editor`: Menulis, mengedit draft artikel, dan mengelola media thumbnail.
  - `User`: Membaca artikel, filter kategori, pencarian teks, komentar, dan like.
- **Content Management**:
  - CRUD artikel dengan image upload & storage linking.
  - SEO-friendly slug generation dan hit-counter tracking per artikel.
  - Status workflow: Draft, Published, Pending, Rejected.
- **Reader Engagement**:
  - Filter kategori dinamis dan pencarian instan.
  - Sistem interaksi komentar dan likes dengan relasi database terindeks.
- **Responsive & Dark Mode**:
  - Tampilan adaptif (mobile, tablet, desktop) dengan dukungan tema gelap.

---

## 🛠️ Instalasi & Menjalankan Lokal

### Prasyarat
- PHP >= 8.2 & Composer
- Node.js >= 18 & NPM
- Database server (MySQL / SQLite)

### Langkah Setup

1. **Clone repository & install dependencies**
   ```bash
   git clone https://github.com/drathekreator/FAKTAnow.git
   cd FAKTAnow
   composer install
   npm install
   ```

2. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Sesuaikan konfigurasi database di file `.env`.

3. **Database Migration & Seeder**
   ```bash
   php artisan migrate --seed
   ```

4. **Storage Link & Asset Build**
   ```bash
   php artisan storage:link
   npm run build
   ```

5. **Jalankan Server Development**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://localhost:8000`.

---

## 📂 Struktur Direktori

```text
FAKTAnow/
├── app/
│   ├── Http/Controllers/     # Controller per resource (Article, Admin, Editor, Auth)
│   ├── Http/Middleware/      # Role validation & authentication guards
│   └── Models/               # Eloquent Models (User, Article, Category, Comment, Like)
├── config/                   # Konfigurasi framework & database
├── database/
│   ├── migrations/           # Skema tabel database
│   └── seeders/              # Data awal kategori & user
├── public/                   # Asset publik & storage link
├── resources/
│   ├── css/ & js/            # Source Tailwind & scripts Vite
│   └── views/                # Blade views (Admin, Editor, Customer, Components)
└── routes/                   # Route web, auth, dan console
```

---

## 👥 Tim Pengembang

Kelompok 5 - Pemrograman Web:
- Indra Fata Azzaky
- Bagas Putra Rinaldi
- Satria Febrian
- Azzaria Nur Febbyasari
- Maulana Krishna Wahyu Agung

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
