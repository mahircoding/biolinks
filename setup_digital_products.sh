#!/bin/bash

# Digital Products System - Quick Setup Script
# Run this script from your biolinks root directory

echo "=== Digital Products System Setup ==="
echo ""

# Create upload directory
echo "Creating upload directories..."
mkdir -p uploads/products
chmod 755 uploads/products
echo "✓ Upload directories created"

# Set permissions
echo "Setting permissions..."
chmod 644 app/models/Product.php
chmod 644 app/models/Order.php
chmod 644 app/controllers/Products.php
chmod 644 app/controllers/Orders.php
chmod 644 app/helpers/Midtrans.php
echo "✓ File permissions set"

echo ""
echo "=== Database Setup Required ==="
echo "Please run the following SQL command to create the required tables:"
echo ""
echo "mysql -u [username] -p [database_name] < database_products.sql"
echo ""
echo "Or import database_products.sql through phpMyAdmin"
echo ""

echo "=== Midtrans Configuration Required ==="
echo "1. Get your Midtrans Server Key and Client Key from Midtrans Dashboard"
echo "2. Add the following to your app configuration:"
echo ""
echo "// Midtrans Configuration"
echo "\$midtrans_server_key = 'your-server-key';"
echo "\$midtrans_client_key = 'your-client-key';"
echo "\$midtrans_is_production = false; // Set true for production"
echo ""
echo "3. Initialize Midtrans in your app bootstrap:"
echo "require_once 'app/helpers/Midtrans.php';"
echo "\Altum\Helpers\Midtrans::init(\$midtrans_server_key, \$midtrans_client_key, \$midtrans_is_production);"
echo ""

echo "=== Routes Available ==="
echo "Public Routes:"
echo "- /products/catalog - Product catalog"
echo "- /products/view/{id} - Product details"
echo ""
echo "Authenticated Routes:"
echo "- /products - Manage products"
echo "- /products/create - Create product"
echo "- /orders - View orders"
echo "- /orders/payment/{id} - Payment page"
echo ""

echo "=== Next Steps ==="
echo "1. Import the database schema"
echo "2. Configure Midtrans credentials"
echo "3. Test by creating a product and making a purchase"
echo "4. Configure email settings for notifications"
echo ""
echo "Setup complete! 🚀"