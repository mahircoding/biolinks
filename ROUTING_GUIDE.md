# Digital Products System - Routing Guide

## URL Routing Structure

Sistem produk digital menggunakan routing berikut:

### 1. `/products` - Product Management (Auth Required)
- **URL**: `https://yourdomain.com/products`
- **Access**: Hanya user yang sudah login
- **Purpose**: Halaman utama untuk manage produk (CRUD)
- **Features**:
  - List semua produk milik user
  - Create, Edit, Delete produk
  - View statistics

### 2. `/products/catalog` - Product Catalog (Public)
- **URL**: `https://yourdomain.com/products/catalog`
- **Access**: Public (semua orang bisa akses)
- **Purpose**: Katalog produk yang bisa dilihat semua orang
- **Features**:
  - Browse semua produk yang aktif
  - Filter dan search produk
  - View product details

### 3. `/product/{product_id}` - Single Product View (Public)
- **URL**: `https://yourdomain.com/product/abc123def456`
- **Access**: Public (tapi ada fitur khusus untuk user login)
- **Purpose**: Halaman detail produk individual
- **Features**:
  - View detail produk lengkap
  - Purchase button (jika belum beli)
  - Download link (jika sudah beli)

### 4. `/orders` - Order Management (Auth Required)
- **URL**: `https://yourdomain.com/orders`
- **Access**: Hanya user yang sudah login
- **Purpose**: Melihat riwayat pembelian
- **Features**:
  - List order history
  - View order details
  - Download purchased products

### 5. `/webhook-midtrans` - Payment Webhook (System)
- **URL**: `https://yourdomain.com/webhook-midtrans`
- **Access**: Midtrans payment gateway only
- **Purpose**: Menerima notifikasi payment dari Midtrans
- **Features**:
  - Process payment confirmation
  - Update order status
  - Send email notifications

## Router Configuration

Di file `app/core/Router.php`, routes sudah dikonfigurasi dengan urutan:

```php
'catalog' => [
    'controller' => 'Products',
    'settings' => [
        'no_authentication_check' => true
    ]
],

'product' => [
    'controller' => 'Products',
    'settings' => [
        'no_authentication_check' => true
    ]
],

'products' => [
    'controller' => 'Products'
],

'orders' => [
    'controller' => 'Orders'
],

'webhook-midtrans' => [
    'controller' => 'Orders',
    'action' => 'webhook',
    'settings' => [
        'no_authentication_check' => true
    ]
]
```

## Important Notes

1. **Route Order**: Route `catalog` harus sebelum `products` untuk menghindari conflict
2. **Authentication**: Route dengan `no_authentication_check: true` bisa diakses tanpa login
3. **Controller Key**: Router menggunakan `$controller_key` untuk menentukan action dalam controller
4. **Parameters**: URL parameters diakses melalui `$this->params[]` array

## Testing URLs

Setelah setup, test dengan URL berikut:
- `/products` - Management dashboard
- `/products/catalog` - Public catalog  
- `/product/test123` - Single product view
- `/orders` - Order history