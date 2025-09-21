# Digital Products System - Setup Guide

## Installation Instructions

### 1. Database Setup

Execute the SQL file to create required tables:

```bash
mysql -u username -p database_name < database_products.sql
```

Or manually run the SQL commands in `database_products.sql` through phpMyAdmin or your preferred database tool.

### 2. Create Upload Directory

Create the products upload directory:

```bash
mkdir -p uploads/products
chmod 755 uploads/products
```

### 3. Midtrans Configuration

Add Midtrans configuration to your settings. You can add this to your existing settings table or configuration file:

```php
// Midtrans Settings
$midtrans_server_key = 'your-midtrans-server-key';
$midtrans_client_key = 'your-midtrans-client-key';
$midtrans_is_production = false; // Set to true for production

// Initialize Midtrans in your app bootstrap
require_once 'app/helpers/Midtrans.php';
\Altum\Helpers\Midtrans::init($midtrans_server_key, $midtrans_client_key, $midtrans_is_production);
```

## Features Implemented

### 1. Product Management
- **Location**: `app/controllers/Products.php`
- **Routes**: 
  - `/products` - Manage your products (logged in users)
  - `/products/create` - Create new product
  - `/products/catalog` - Public product catalog
  - `/products/view/{product_id}` - View product details

### 2. Order Management
- **Location**: `app/controllers/Orders.php`
- **Routes**:
  - `/orders` - View your orders (logged in users)
  - `/orders/create/{product_id}` - Create new order
  - `/orders/payment/{order_id}` - Payment page
  - `/orders/success/{order_id}` - Success page
  - `/webhook-midtrans` - Midtrans webhook handler

### 3. Database Tables

#### products
- `product_id` - Unique product identifier
- `user_id` - Product owner
- `name` - Product name
- `description` - Product description
- `price` - Product price
- `image` - Product image filename
- `digital_link` - External access link
- `status` - Active/inactive status
- `views` - View counter
- `sales` - Sales counter
- `settings` - JSON settings
- `datetime` - Creation date

#### orders
- `order_id` - Unique order identifier
- `transaction_id` - Payment gateway transaction ID
- `user_id` - Buyer
- `product_id` - Purchased product
- `amount` - Order amount
- `payment_method` - Payment method (midtrans)
- `status` - Order status (pending/completed/failed)
- `payment_details` - JSON payment gateway response
- `datetime` - Order creation date
- `completed_datetime` - Order completion date

## Usage Guide

### For Product Sellers:
1. Login to your account
2. Navigate to `/products`
3. Click "Add Product" to create a new digital product
4. Fill in product details:
   - Name and description
   - Price
   - Upload product image
   - Add external access link for digital delivery
5. Set product as active to make it visible in catalog

### For Customers:
1. Browse products at `/products/catalog`
2. Click on a product to view details
3. If not logged in, register/login first
4. Click "Buy Now" to create order
5. Complete payment through Midtrans
6. Access purchased product from orders page

## Email Notifications

The system automatically sends email confirmations when:
- Order is completed successfully
- Email includes order details and product access link

## Midtrans Integration

### Webhook Setup
Configure Midtrans webhook URL in your Midtrans dashboard:
```
https://yourdomain.com/webhook-midtrans
```

### Payment Flow
1. Customer clicks "Buy Now"
2. Order created with "pending" status
3. Customer redirected to payment page
4. Payment processed through Midtrans
5. Webhook updates order status
6. Email sent to customer
7. Customer can access product

## Testing

### Test the complete flow:
1. Create a test product
2. Use a different user account to purchase
3. Complete payment (use Midtrans sandbox for testing)
4. Verify email notification is sent
5. Check order appears in buyer's orders list
6. Verify product access link works

## Security Notes

1. **File Uploads**: Product images are validated for allowed extensions
2. **Input Sanitization**: All user inputs are cleaned using `Database::clean_string()`
3. **Authentication**: Product management requires user login
4. **Authorization**: Users can only manage their own products
5. **Payment Security**: Webhook signature verification implemented

## Customization

### Adding New Product Fields
1. Add column to `products` table
2. Update `Product` model methods
3. Add field to product forms
4. Update views to display new field

### Payment Gateway Integration
The system is designed to easily add other payment gateways:
1. Create new helper class (like `Midtrans.php`)
2. Update `Orders` controller payment methods
3. Add new webhook handler

## Troubleshooting

### Common Issues:
1. **Upload Directory**: Ensure `uploads/products/` is writable
2. **Email Not Sending**: Check PHP mail configuration
3. **Payment Webhook**: Verify Midtrans webhook URL is accessible
4. **Database Connection**: Ensure all tables are created correctly

### Debug Mode:
Add debug output in controllers to trace issues:
```php
error_log("Debug: " . print_r($variable, true));
```

## Support

For customizations or issues, refer to the biolinks documentation or contact the development team.