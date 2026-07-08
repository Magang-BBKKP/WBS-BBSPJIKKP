# Product Requirements Document (PRD)

# Website Whistleblowing System (WBS)
## BBSPJIKKP Bersih
**Berintegritas • Siap Melayani • Hebat Tanpa Korupsi**

---

# Document Information

| Item | Value |
|------|------|
| Project | Website Whistleblowing System (WBS) |
| Client | Balai Besar Standardisasi dan Pelayanan Jasa Industri Kulit, Karet, dan Plastik (BBSPJIKKP) |
| Framework | Laravel 13 |
| Database | MySQL |
| Deployment | Docker Compose |
| Version | 1.0 |
| Status | Draft |

---

# 1. Background

BBSPJIKKP berkomitmen untuk mewujudkan tata kelola pemerintahan yang baik (Good Governance) melalui pembangunan Zona Integritas menuju WBK dan WBBM.

Sebagai bagian dari komitmen tersebut, diperlukan sebuah Website Whistleblowing System (WBS) yang mampu menjadi media pelaporan dugaan pelanggaran secara aman, transparan, terdokumentasi, serta menjaga kerahasiaan identitas pelapor.

Website harus mendukung proses bisnis sesuai SOP Pengelolaan Whistle Blowing System BBSPJIKKP mulai dari penerimaan laporan, verifikasi, investigasi, penetapan tindak lanjut, hingga monitoring penyelesaian.

---

# 2. Product Vision

Membangun platform pelaporan pelanggaran yang:

- Aman
- Transparan
- Mudah digunakan
- Mendukung pelaporan anonim
- Mempermudah investigasi
- Mudah dipelihara
- Mudah dikembangkan
- Mudah di-deploy

---

# 3. Objectives

Website harus mampu:

- Menjadi media pelaporan resmi.
- Menjaga identitas pelapor.
- Mendokumentasikan seluruh proses investigasi.
- Mempermudah monitoring laporan.
- Mendukung audit internal.
- Mengurangi proses manual.
- Mendukung pembangunan Zona Integritas.

---

# 4. Users

## Public / Whistleblower

Hak akses:

- Membuat laporan
- Upload bukti
- Tracking laporan
- Memberikan klarifikasi
- Memilih anonim

---

## Tim WBS

Hak akses:

- Verifikasi laporan
- Meminta klarifikasi
- Menentukan validitas
- Monitoring

---

## Investigator

Hak akses:

- Melihat laporan
- Menjalankan investigasi
- Upload hasil investigasi
- Memberikan rekomendasi

---

## Kepala BBSPJIKKP

Hak akses:

- Menerima laporan valid
- Membentuk Tim Investigasi
- Menelaah hasil investigasi
- Menentukan tindak lanjut

---

## Super Admin

Hak akses penuh terhadap sistem.

---

# 5. Business Process

Workflow sistem mengikuti SOP BBSPJIKKP.

Pelapor

↓

Mengirim Laporan

↓

Nomor Registrasi dibuat

↓

Verifikasi Tim WBS

↓

Valid / Klarifikasi / Ditolak

↓

Kepala BBSPJIKKP

↓

Pembentukan Tim Investigasi

↓

Investigasi

↓

Telaah Hasil

↓

Tindak Lanjut

↓

Monitoring

↓

Selesai

---

# 6. Jenis Pelanggaran

Website harus menyediakan kategori:

- Korupsi
- Suap
- Gratifikasi
- Benturan Kepentingan
- Kecurangan
- Pencurian
- Pembocoran Data
- Pelanggaran Hukum
- Pelanggaran Akuntansi
- Pelanggaran Etika

Kategori harus dapat ditambah melalui Master Data.

---

# 7. Functional Modules

## 1. Landing Page

Fitur:

- Hero Banner
- Tentang BBSPJIKKP Bersih
- Komitmen Integritas
- Jenis Pelanggaran
- Cara Melapor
- FAQ
- Kontak
- Footer

---

## 2. Authentication

- Login
- Logout
- Reset Password
- Forgot Password
- Profile

---

## 3. Role & Permission

Role:

- Super Admin
- Tim WBS
- Investigator
- Kepala BBSPJIKKP

Permission:

- View
- Create
- Update
- Delete
- Approve

---

## 4. User Management

- CRUD User
- Aktivasi akun
- Role Assignment

---

## 5. Pelaporan

Pelapor dapat:

- Mengisi formulir
- Upload banyak bukti
- Melapor anonim
- Mendapat nomor registrasi

---

## 6. Tracking

Pelapor dapat:

- Melihat status
- Timeline
- Permintaan klarifikasi
- Riwayat

---

## 7. Dashboard

Menampilkan:

- Statistik
- Grafik
- Laporan terbaru
- Progress Investigasi

---

## 8. Verifikasi

Tim WBS dapat:

- Memvalidasi laporan
- Meminta klarifikasi
- Menolak laporan

---

## 9. Investigasi

Investigator dapat:

- Membuat timeline
- Upload dokumen
- Memberikan hasil investigasi

---

## 10. Tindak Lanjut

Kepala BBSPJIKKP dapat:

- Menelaah hasil
- Menentukan tindakan

Pilihan:

- Pembinaan
- Teguran
- Hukuman Disiplin
- Pemutusan Kontrak
- Pelaporan APH
- Perbaikan Sistem

---

## 11. Monitoring

Monitoring:

- Efektivitas
- Penyelesaian
- Pencegahan

---

## 12. Notification

- Email
- Notifikasi Sistem

---

## 13. Audit Log

Mencatat seluruh aktivitas.

---

## 14. Master Data

CRUD:

- Kategori
- Unit
- Status
- Prioritas

---

## 15. Pengaturan Sistem

- Logo
- SMTP
- Backup
- Konfigurasi

---

# 8. Non Functional Requirements

## Security

- HTTPS
- CSRF
- XSS Protection
- SQL Injection Protection
- Audit Trail
- Password Hashing
- Role Based Access Control
- Secure File Upload

---

## Performance

- Response < 3 detik
- Pagination
- Lazy Loading
- Queue

---

## Reliability

- Backup Database
- Error Logging
- Queue Retry

---

## Maintainability

Menggunakan:

- MVC
- Service Layer
- Repository Pattern
- Form Request Validation
- Policy
- Middleware
- Modular Architecture

---

## Scalability

Website harus mudah dikembangkan untuk:

- REST API
- Mobile Apps
- SSO
- Multi Instansi

---

# 9. Technical Stack

Backend

- Laravel 13

Frontend

- Blade
- Bootstrap 5

Database

- MySQL

Storage

- Laravel Storage

Authentication

- Laravel Breeze

Permission

- Spatie Laravel Permission

Deployment

- Docker
- Docker Compose
- Nginx

Version Control

- Git

Repository

- GitHub

---

# 10. Project Structure

Project dibangun secara modular.

01 Setup

02 Landing Page

03 Authentication

04 User Management

05 Role Permission

06 Master Data

07 Reporting

08 Tracking

09 Dashboard

10 Verification

11 Investigation

12 Follow Up

13 Monitoring

14 Notification

15 Audit Log

16 Settings

17 API

18 Deployment

---

# 11. Acceptance Criteria

Website dianggap selesai apabila:

✓ Seluruh SOP dapat diimplementasikan.

✓ Pelapor dapat membuat laporan.

✓ Nomor registrasi dapat digunakan untuk tracking.

✓ Tim WBS dapat melakukan verifikasi.

✓ Kepala dapat membentuk Tim Investigasi.

✓ Investigator dapat mengunggah hasil investigasi.

✓ Kepala dapat menetapkan tindak lanjut.

✓ Monitoring berjalan.

✓ Audit Log aktif.

✓ Website dapat dijalankan menggunakan Docker Compose.

✓ Dokumentasi instalasi tersedia.

✓ Seluruh modul mengikuti Role & Permission.

---

# 12. Future Roadmap

Phase 2

- Mobile Application

- WhatsApp Notification

- Digital Signature

- SSO Kementerian Perindustrian

- Dashboard Analytics

- Export PDF

- Export Excel

- REST API

- Multi Bahasa

- AI Classification untuk kategori laporan