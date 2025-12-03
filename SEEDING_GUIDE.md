# Seeding Guide - FAKTAnow

## Cara Menjalankan Seeder

### 1. Seed Semua Data (Fresh Install)

Untuk fresh install dengan drop semua tabel dan seed ulang:

```bash
php artisan migrate:fresh --seed
```

**Peringatan**: Command ini akan menghapus SEMUA data di database!

### 2. Seed Hanya Categories

Jika hanya ingin seed categories tanpa menghapus data lain:

```bash
php artisan db:seed --class=CategorySeeder
```

### 3. Seed Tanpa Drop Tables

Jika ingin menjalankan semua seeder tanpa drop tables:

```bash
php artisan db:seed
```

## Kategori yang Tersedia

Setelah menjalankan CategorySeeder, kategori berikut akan tersedia:

1. **Politik** (slug: `politik`)
2. **Ekonomi** (slug: `ekonomi`)
3. **Teknologi** (slug: `teknologi`)
4. **Olahraga** (slug: `olahraga`)
5. **Hiburan** (slug: `hiburan`)
6. **Kesehatan** (slug: `kesehatan`)
7. **Pendidikan** (slug: `pendidikan`)

## Verifikasi Seeding

Untuk memverifikasi bahwa categories sudah ter-seed dengan benar:

```bash
php artisan tinker --execute="echo 'Total categories: ' . App\Models\Category::count();"
```

Atau masuk ke tinker dan jalankan:

```bash
php artisan tinker
```

Kemudian di dalam tinker:

```php
App\Models\Category::all();
```

## Troubleshooting

### Error: Column 'slug' not found

Jika mendapat error ini, pastikan migration sudah dijalankan:

```bash
php artisan migrate
```

### Error: Duplicate entry

Jika kategori sudah ada dan mendapat error duplicate, CategorySeeder menggunakan `updateOrCreate` yang akan update data yang sudah ada.

### Categories Tidak Muncul di Navbar

Pastikan:

1. Seeder sudah dijalankan dengan benar
2. Controller mengirim variabel `$categories` ke view:
   ```php
   $categories = Category::all() ?? collect();
   ```
3. View menggunakan loop untuk menampilkan categories:
   ```blade
   @foreach($categories as $category)
       <a href="{{ route('category.show', $category->slug) }}">
           {{ $category->name }}
       </a>
   @endforeach
   ```

### Categories Tidak Muncul di Form

Pastikan di view form (create/edit article) ada:

```blade
<select name="category_id">
    <option value="">Pilih Kategori</option>
    @foreach(\App\Models\Category::all() as $category)
        <option value="{{ $category->id }}">
            {{ $category->name }}
        </option>
    @endforeach
</select>
```

## Menambah Kategori Baru

Untuk menambah kategori baru, edit file `database/seeders/CategorySeeder.php`:

```php
$categories = [
    // ... existing categories
    [
        'name' => 'Kategori Baru',
        'slug' => 'kategori-baru',
        'created_at' => now(),
        'updated_at' => now(),
    ],
];
```

Kemudian jalankan seeder lagi:

```bash
php artisan db:seed --class=CategorySeeder
```

## Production Deployment

Untuk production, jalankan:

```bash
php artisan migrate --force
php artisan db:seed --class=CategorySeeder --force
```

Flag `--force` diperlukan karena di production `APP_ENV=production`.

---

**Last Updated**: December 3, 2025  
**Version**: 1.1.0
