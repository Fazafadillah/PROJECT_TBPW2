## Cara Menjalankan Project
### 1) Persiapan
Pastikan kamu sudah punya:
- XAMPP / Laragon
- Apache + MySQL aktif
- phpMyAdmin

### 2) Pindahkan folder project
Simpan folder project ke:
- `xampp/htdocs/medwell`
atau
- `laragon/www/medwell`

### 3) Import Database
1. Buka `phpMyAdmin`
2. Buat database baru: `medwell`
3. copy file: `medwell.sql`

### 4) Atur koneksi DB
Edit file:
`config/db.php`

Contoh:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "medwell";

### 5) Buka Project
http://localhost/PROJECT_TBPW2/auth/login.php

1) Alur Register & Login

Pasien membuka halaman Register

Pasien mengisi nama, email, password

Sistem menyimpan akun ke tabel users dengan role pasien

User login melalui halaman Login

Sistem memeriksa akun:

tabel users untuk role admin/pasien

tabel doctors untuk role doctor

Jika login berhasil, session dibuat dan user diarahkan ke dashboard sesuai role

2) Alur Appointment (Janji Berobat)

Pasien login

Pasien membuka menu Buat Appointment

Pasien memilih:

dokter

tanggal

jam

keluhan

Data masuk ke tabel appointments dengan status awal pending

Admin membuka menu Appointments

Admin memproses appointment:

Approve → status menjadi approved

Cancel → status menjadi cancel

3) Alur Pemeriksaan Dokter (Rekam Medis & Resep)

Dokter login

Dokter membuka menu Appointments Dokter

Dokter hanya bisa input rekam medis jika status appointment:

approved atau done

Dokter mengisi rekam medis:

tekanan darah

berat badan

diagnosa

tindakan

catatan dokter

Data disimpan ke tabel medical_records

Sistem otomatis mengubah status appointment menjadi done

Dokter dapat menambahkan resep obat:

nama obat

dosis

aturan pakai

jumlah

Data resep disimpan ke tabel prescriptions

4) Alur Riwayat Pasien (Rekam Medis)

Pasien login

Pasien membuka menu Rekam Medis Saya

Sistem menampilkan hasil pemeriksaan dokter:

diagnosa

tindakan

catatan dokter

resep obat
