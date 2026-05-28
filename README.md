# 📦 POS System (Laravel + Reverb Realtime WebSocket)

Sistem **Point of Sale (POS)** berbasis Laravel dengan fitur realtime menggunakan **Laravel Reverb**, memungkinkan update transaksi, stok, dan dashboard secara langsung tanpa refresh halaman.

Sistem ini dirancang untuk kebutuhan kasir modern, manajemen stok, dan laporan penjualan secara efisien dan cepat.

---

## 🚀 Demo Project

> (Opsional jika ada deployment)

- 🌐 Live Demo: `https://your-domain.com`
- 📁 Admin Panel: `/admin`
- 💳 Kasir: `/cashier`

---

## ✨ Fitur Utama

- 🔐 Authentication (Login & Register)
- 👥 Role Management (Admin & Kasir)
- 📦 Manajemen Produk
- 📊 Manajemen Stok Barang
- 🧾 Sistem Transaksi POS
- 📈 Laporan Penjualan
- 🔔 Realtime Notification (Laravel Reverb)
- ⚡ Update dashboard tanpa reload
- 🧾 Riwayat transaksi lengkap
- 📱 Responsive UI (mobile friendly)

---

## 🛠️ Tech Stack

- PHP 8+
- Laravel Framework 10/11
- MySQL / MariaDB
- Laravel Reverb (WebSocket Server)
- Laravel Echo
- Vite
- Bootstrap 
- Node.js (frontend build tools)

---

## ⚙️ Instalasi Project

### 1. Clone Repository

```bash
git clone https://github.com/username/pos-laravel-reverb.git
cd pos-laravel-reverb
```

### 2. Install Dependency

```bash
composer install
npm install
npm run dev
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

---

## 🗄️ Setup Database

Edit file `.env`:

```env
DB_DATABASE=pos_db
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan migration:

```bash
php artisan migrate --seed
```

---

## ⚡ Setup Laravel Reverb (Realtime WebSocket)

### 1. Install Reverb

```bash
composer require laravel/reverb
```

### 2. Install Config Reverb

```bash
php artisan reverb:install
```

### 3. Konfigurasi `.env`

```env
REVERB_APP_ID=pos-app
REVERB_APP_KEY=pos-key
REVERB_APP_SECRET=pos-secret

REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

BROADCAST_CONNECTION=reverb
```

### 4. Jalankan Reverb Server

```bash
php artisan reverb:start
```

---

## 📡 Setup Laravel Echo (Frontend Realtime)

Install dependency:

```bash
npm install laravel-echo pusher-js
```

Konfigurasi `resources/js/bootstrap.js`:

```js
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 8080,
    forceTLS: false,
    disableStats: true,
});
```

---

## 🔔 Contoh Event Realtime POS

### Event: Transaksi Baru

```php
class NewTransactionCreated implements ShouldBroadcast
{
    public $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        return new Channel('pos-transactions');
    }
}
```

### Trigger Event

```php
event(new NewTransactionCreated($transaction));
```

### Frontend Listener

```js
Echo.channel('pos-transactions')
    .listen('NewTransactionCreated', (e) => {
        console.log('Transaksi baru:', e.transaction);
        // update UI realtime
    });
```
