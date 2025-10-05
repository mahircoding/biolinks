# Digital Products Sales System

This document describes the implementation of a digital products sales system for the Biolink.bio platform.

## Features Implemented

1. **Product Management**
   - Admin can create, update, and delete digital products
   - Products have name, description, image, access URL, and price
   - Products can be set as active or inactive

2. **Order Management**
   - Admin can view and manage orders
   - Orders have customer details (name, email, WhatsApp)
   - Order status tracking (pending, paid, failed, refunded)

3. **Frontend Product Display**
   - Public product listing page
   - Individual product detail pages
   - No login required to view products

4. **Checkout Process**
   - Customers can purchase without creating an account
   - Collects customer name, email, and WhatsApp number
   - Redirects to payment page after checkout

5. **Payment Integration**
   - Midtrans payment gateway integration
   - Webhook for payment status updates
   - Test payment simulation for development

6. **Email Delivery**
   - Automatic email sent to customers after successful payment
   - Includes product access URL

## Implementation Details

### Database Schema

Two new tables were created:

1. `digital_products` - Stores product information
2. `digital_orders` - Stores order information

### Files Created

#### Models
- `app/models/DigitalProduct.php` - Product model
- `app/models/DigitalOrder.php` - Order model

#### Controllers
- `app/controllers/DigitalProduct.php` - Product controller
- `app/controllers/DigitalOrder.php` - Order controller

#### Admin Views
- `themes/altum/views/admin/digital-product/index.php` - Product listing
- `themes/altum/views/admin/digital-product/create.php` - Create product form
- `themes/altum/views/admin/digital-product/update.php` - Update product form
- `themes/altum/views/admin/digital-order/index.php` - Order listing
- `themes/altum/views/admin/digital-order/view.php` - View order details
- `themes/altum/views/admin/digital-order/update-status.php` - Update order status

#### Frontend Views
- `themes/altum/views/digital-product/public-index.php` - Public product listing
- `themes/altum/views/digital-product/public-view.php` - Public product details
- `themes/altum/views/digital-order/checkout.php` - Checkout form
- `themes/altum/views/digital-order/payment.php` - Payment page

#### Routes
- Added routes in `app/core/Router.php` for all digital product functionality

#### Admin Settings
- Added Midtrans settings to admin panel in `themes/altum/views/admin/settings/index.php`

## How to Use

### For Administrators

1. Log into the admin panel
2. Navigate to "Digital Products" in the sidebar
3. Create products with:
   - Name
   - Description
   - Price
   - Access URL (where customers will be directed after purchase)
   - Optional image
   - Status (active/inactive)
4. View orders in the "Digital Orders" section
5. Update order statuses as needed

### For Customers

1. Visit the products page (URL: `/digital-products`)
2. Browse available products
3. Click on a product to view details
4. Click "Buy Now" to start checkout
5. Enter name, email, and WhatsApp number
6. Proceed to payment page
7. Complete payment (in test mode, use the simulation buttons)
8. Receive email with product access information

## Payment Integration

The system is integrated with Midtrans payment gateway:

1. Admin can enable/disable Midtrans in settings
2. Configure server key, client key, and environment
3. Payment notifications are handled via webhook
4. Order status is automatically updated based on payment status

## Testing

A test script is available at `test_digital_products.php` to verify the implementation.

## Security Notes

- All user inputs are sanitized
- CSRF protection is implemented for forms
- File uploads are validated
- Admin-only actions are protected

## Future Improvements

1. Add product categories
2. Implement discount codes
3. Add product reviews and ratings
4. Create seller dashboard for multi-vendor support
5. Add more payment gateways
6. Implement subscription-based products