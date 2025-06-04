# 🚀 PERPUSTAKAAN SMP ALFALAH ASSALAM

Dokumentasi proyek ini menjelaskan alur kerja dalam pengembangan fitur menggunakan Git branching strategy dan standar penamaan commit. Proyek ini menggunakan Laravel sebagai backend framework dengan autentikasi JWT.

---

## 📂 Struktur Branch

Branch utama yang digunakan dalam proyek ini:

- `main`: Branch utama yang berisi kode stabil dan siap rilis.
- `develop`: Branch pengembangan aktif. Semua fitur baru di-merge ke sini.

### ⬆️ Alur Pembuatan Branch Fitur

Setiap fitur baru dikembangkan pada branch terpisah yang diturunkan dari `develop` dengan format:

```
nama-pengembang/nama-fitur
```

**Contoh:**
```
bintang/login-page
gerrard/export-excel
```

> Setelah fitur selesai, ajukan pull request (PR) ke `develop`. Jangan langsung merge ke `main`.

---

## 📝 Konvensi Penamaan Commit

Gunakan format penamaan commit berikut agar konsisten dan mudah ditelusuri:

```
<TYPE>: <short description>
```

### Jenis Commit:
| Tag        | Deskripsi                                      |
|------------|-----------------------------------------------|
| `FEAT`     | Penambahan fitur baru                         |
| `FIX`      | Perbaikan bug                                 |
| `CHORE`    | Perubahan non-fungsional (e.g. dependencies)  |
| `REFACTOR` | Perubahan kode tanpa mengubah perilaku        |
| `DOCS`     | Perubahan dokumentasi                         |
| `TEST`     | Penambahan/perbaikan testing                  |
| `STYLE`    | Format kode tanpa mengubah logic              |
| `PERF`     | Peningkatan performa                          |
| `CI`       | Konfigurasi CI/CD                             |
| `BUILD`    | Perubahan sistem build atau package manager   |

### Contoh Commit:
```
FEAT: implement login with JWT
FIX: validate email field on register
CHORE: update composer packages
```

---

## ⚙️ Setup Project

Berikut langkah untuk setup project di lokal:

```bash
# Install semua dependency PHP
composer install

# Generate application key
php artisan key:generate

# Jalankan migrasi database
php artisan migrate

# Seed database dengan data awal
php artisan db:seed
```

> Pastikan Anda telah mengatur konfigurasi database pada file `.env` sebelum menjalankan perintah di atas.

---

## 🔁 Alur Penggabungan (Merge Flow)

1. Buat branch dari `develop`
2. Kembangkan fitur Anda
3. Push ke remote repository
4. Ajukan Pull Request ke `develop`
5. Setelah semua fitur stabil → merge `develop` ke `main` untuk rilis

---

## 👥 Kolaborasi Tim

- Lakukan `git pull origin develop` sebelum memulai fitur baru.
- Gunakan draft PR untuk memantau progress.
- Sertakan deskripsi lengkap dan jelas pada setiap PR.
- Review dan testing kode sebelum di-merge ke branch `develop`.
- Jangan lupa jalankan `./vendor/bin/pint` sebelum commit

---

## 📌 Catatan

- Jangan commit file `.env` atau file konfigurasi sensitif lainnya.
- Gunakan `.gitignore` untuk mengecualikan file yang tidak diperlukan.
- Gunakan tools seperti Postman atau Insomnia untuk menguji endpoint API.
- Setiap membuat contoller atau view baru pastikan menggunakan livewire.
- Pastikan menggunakan `w-10/12 mx-auto` untuk container.

---
