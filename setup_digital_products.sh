#!/bin/bash

# Digital Products Setup Script
# This script helps set up the digital products system

echo "🚀 Digital Products Setup Script"
echo "================================"
echo ""

# Check if we're in the correct directory
if [ ! -f "app/init.php" ]; then
    echo "❌ Error: Please run this script from the project root directory"
    exit 1
fi

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "❌ Error: MySQL is not installed"
    echo "💡 Please install MySQL and try again"
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ Error: PHP is not installed"
    echo "💡 Please install PHP and try again"
    exit 1
fi

# Check if required PHP extensions are installed
echo "📊 Checking PHP extensions..."
required_extensions=("mysqli" "curl" "gd" "json" "mbstring")
missing_extensions=()

for extension in "${required_extensions[@]}"; do
    if ! php -m | grep -q "^$extension$"; then
        missing_extensions+=("$extension")
    fi
done

if [ ${#missing_extensions[@]} -eq 0 ]; then
    echo "✅ All required PHP extensions are installed"
else
    echo "❌ Missing PHP extensions: ${missing_extensions[*]}"
    echo "💡 Please install the missing extensions and try again"
    exit 1
fi

# Create necessary directories
echo "📁 Creating necessary directories..."
directories=("uploads" "uploads/products" "uploads/users")

for dir in "${directories[@]}"; do
    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        echo "✅ Created directory: $dir"
    else
        echo "✅ Directory already exists: $dir"
    fi
    
    # Set proper permissions
    chmod 755 "$dir"
    echo "✅ Set permissions for: $dir"
done

# Import database schema
echo "📊 Importing database schema..."
if [ -f "database_products.sql" ]; then
    # Get database credentials from config
    DB_HOST=$(grep "DATABASE_SERVER" app/config/config.php | cut -d "'" -f 4)
    DB_USER=$(grep "DATABASE_USERNAME" app/config/config.php | cut -d "'" -f 4)
    DB_PASS=$(grep "DATABASE_PASSWORD" app/config/config.php | cut -d "'" -f 4)
    DB_NAME=$(grep "DATABASE_NAME" app/config/config.php | cut -d "'" -f 4)
    
    # Import the schema
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < database_products.sql
    
    if [ $? -eq 0 ]; then
        echo "✅ Database schema imported successfully"
    else
        echo "❌ Error importing database schema"
        exit 1
    fi
else
    echo "❌ Error: database_products.sql not found"
    exit 1
fi

# Create .htaccess file for uploads directory
echo "🔒 Creating .htaccess file for uploads directory..."
cat > uploads/.htaccess << EOF
# Prevent directory listing
Options -Indexes

# Prevent access to hidden files
<Files ".*">
    Require all denied
</Files>

# Prevent access to certain file types
<FilesMatch "\.(php|pl|py|jsp|asp|sh|cgi)$">
    Require all denied
</FilesMatch>

# Allow access to image, video, and audio files
<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|mp4|webm|ogg|mp3|wav|pdf|zip|rar|7z|exe|dmg|iso)$">
    Require all granted
</FilesMatch>
EOF

echo "✅ Created .htaccess file for uploads directory"

# Create .htaccess file for products uploads directory
echo "🔒 Creating .htaccess file for products uploads directory..."
cat > uploads/products/.htaccess << EOF
# Prevent directory listing
Options -Indexes

# Prevent access to hidden files
<Files ".*">
    Require all denied
</Files>

# Prevent access to certain file types
<FilesMatch "\.(php|pl|py|jsp|asp|sh|cgi)$">
    Require all denied
</FilesMatch>

# Allow access to image, video, and audio files
<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|mp4|webm|ogg|mp3|wav|pdf|zip|rar|7z|exe|dmg|iso)$">
    Require all granted
</FilesMatch>
EOF

echo "✅ Created .htaccess file for products uploads directory"

# Create .htaccess file for users uploads directory
echo "🔒 Creating .htaccess file for users uploads directory..."
cat > uploads/users/.htaccess << EOF
# Prevent directory listing
Options -Indexes

# Prevent access to hidden files
<Files ".*">
    Require all denied
</Files>

# Prevent access to certain file types
<FilesMatch "\.(php|pl|py|jsp|asp|sh|cgi)$">
    Require all denied
</FilesMatch>

# Allow access to image files
<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg)$">
    Require all granted
</FilesMatch>
EOF

echo "✅ Created .htaccess file for users uploads directory"

# Create a sample configuration file for payment gateways
echo "⚙️ Creating sample payment gateway configuration..."
cat > payment_gateways_sample.php << EOF
<?php
// Sample Payment Gateway Configuration
// Copy this file to payment_gateways.php and update with your credentials

return [
    'midtrans' => [
        'server_key' => 'YOUR_MIDTRANS_SERVER_KEY',
        'client_key' => 'YOUR_MIDTRANS_CLIENT_KEY',
        'production' => false, // Set to true for production
    ],
    
    'duitku' => [
        'merchant_key' => 'YOUR_DUITKU_MERCHANT_KEY',
        'merchant_code' => 'YOUR_DUITKU_MERCHANT_CODE',
        'url' => 'https://sandbox.duitku.com/webapi/api/merchant',
        'production' => false, // Set to true for production
    ],
];
EOF

echo "✅ Created payment_gateways_sample.php"

# Create a test product
echo "📦 Creating test product..."
php -r "
require_once 'app/init.php';

// Create a test product
\$product_data = [
    'user_id' => 1,
    'name' => 'Test Digital Product',
    'description' => 'This is a test digital product for demonstration purposes.',
    'price' => 50000,
    'image' => null,
    'digital_link' => 'https://example.com/test-product.zip',
    'status' => 1,
    'settings' => json_encode(['type' => 'zip', 'size' => '10MB']),
];

// Generate product ID
\$product_id = generate_unique_id(10);

// Insert into database
\$query = \"INSERT INTO products (product_id, user_id, name, description, price, image, digital_link, status, views, sales, settings, datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)\";
\$datetime = date('Y-m-d H:i:s');
\$params = [\$product_id, \$product_data['user_id'], \$product_data['name'], \$product_data['description'], \$product_data['price'], \$product_data['image'], \$product_data['digital_link'], \$product_data['status'], 0, 0, \$product_data['settings'], \$datetime];

\$connection = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
\$stmt = \$connection->prepare(\$query);
\$stmt->bind_param('ssissssissss', ...\$params);
\$stmt->execute();

if (\$stmt->affected_rows > 0) {
    echo \"✅ Created test product with ID: \$product_id\\n\";
} else {
    echo \"❌ Error creating test product\\n\";
}
"

# Create a comprehensive README file
echo "📝 Creating comprehensive README..."
cat > DIGITAL_PRODUCTS_README.md << EOF
# Digital Products System - Implementation Guide

## 🚀 Overview

This system allows users to sell digital products directly from their bio links, similar to platforms like Lynk.id. It includes features for product management, order processing, payment integration, and automatic delivery.

## ✨ Features

### Product Management
- Create, edit, and delete digital products
- Upload product images
- Set custom prices and descriptions
- Configure digital delivery links
- Track product views and sales

### User Stores
- Personal store pages (username.domain.com)
- Customizable store profiles
- Product catalog display
- Sales statistics

### Order Management
- Simple checkout process
- Guest checkout available
- Order tracking and status management
- Customer information collection

### Payment Integration
- Midtrans payment gateway
- Duitku payment gateway (Indonesian local payment)
- Webhook support for automatic status updates
- Secure payment processing

### Email Notifications
- Automatic order confirmation emails
- Product delivery notifications
- Professional HTML email templates
- Customizable email content

## 📁 File Structure

\`\`\`
app/
├── controllers/
│   ├── Products.php      # Product management controller
│   ├── Orders.php        # Order management controller
│   └── Store.php         # Store pages controller
├── models/
│   ├── Product.php       # Product model
│   └── Order.php         # Order model
├── helpers/
│   ├── Midtrans.php      # Midtrans integration
│   └── Duitku.php        # Duitku integration
└── core/
    └── Router.php        # Updated with new routes

themes/altum/views/
├── products/             # Product management views
├── orders/               # Order management views
├── store/                # Store pages
└── partials/
    └── email_order_confirmation.php  # Email template

database_products.sql     # Database schema
setup_digital_products.sh # Setup script
test_digital_products_flow.php # Test script
\`\`\`

## 🔧 Installation

### 1. Database Setup

Import the database schema:
\`\`\`
mysql -u your_username -p your_database < database_products.sql
\`\`\`

### 2. Run Setup Script

Execute the setup script:
\`\`\`
chmod +x setup_digital_products.sh
./setup_digital_products.sh
\`\`\`

### 3. Configure Payment Gateways

Copy the sample configuration and update with your credentials:
\`\`\`
cp payment_gateways_sample.php payment_gateways.php
\`\`\`

Edit `payment_gateways.php` with your actual API keys.

### 4. Update Configuration

Add payment gateway credentials to `app/config/config.php`:
\`\`\`
define('MIDTRANS_SERVER_KEY', 'your_server_key');
define('MIDTRANS_CLIENT_KEY', 'your_client_key');
define('DUITKU_MERCHANT_KEY', 'your_merchant_key');
define('DUITKU_MERCHANT_CODE', 'your_merchant_code');
\`\`\`

## 🎯 Usage

### For Sellers

1. **Login** to your account
2. **Navigate** to Products section
3. **Create** new product with:
   - Name and description
   - Price in IDR
   - Product image
   - Digital delivery link
4. **Publish** the product
5. **Share** your store link

### For Customers

1. **Browse** products via store links
2. **View** product details
3. **Login/Register** (optional for guest checkout)
4. **Purchase** with payment gateway
5. **Receive** email with download link

## 📊 Testing

Run the test script to verify everything is working:
\`\`\`
php test_digital_products_flow.php
\`\`\`

## 🔒 Security Features

- Input validation and sanitization
- File upload restrictions
- SQL injection protection
- Webhook signature verification
- Secure download links
- Session management

## 📈 Analytics & Reporting

- Product view tracking
- Sales statistics
- Revenue tracking
- Popular products
- Customer analytics

## 🎨 Customization

### Store Pages
- Customize store appearance
- Add social media links
- Configure store information
- Set up custom domains

### Email Templates
- Customize email content
- Add branding elements
- Configure delivery messages
- Set up automated sequences

## 🚀 Deployment

### Production Setup

1. **Update payment gateway credentials** to production mode
2. **Configure email server** settings
3. **Set up SSL certificates** for secure payments
4. **Configure file upload limits** for large digital files
5. **Set up backup system** for database and uploads

### Performance Optimization

1. **Enable caching** for product listings
2. **Optimize images** for faster loading
3. **Use CDN** for static assets
4. **Configure database indexing** for better performance

## 🛠️ Troubleshooting

### Common Issues

1. **Payment not working**
   - Check payment gateway credentials
   - Verify webhook URLs
   - Check server SSL configuration

2. **Email not sending**
   - Check email server configuration
   - Verify SMTP settings
   - Check spam filters

3. **File upload issues**
   - Check directory permissions
   - Verify upload limits
   - Check file type restrictions

### Debug Mode

Enable debug mode in config for detailed error information:
\`\`\`
define('DEBUG_MODE', true);
\`\`\`

## 📞 Support

For technical support:
- Check the documentation
- Review the test script results
- Contact support with error details
- Provide system information

## 🔄 Updates

The system is regularly updated with:
- Security patches
- New payment gateways
- Performance improvements
- Feature enhancements

---

**🎉 Digital Products System Successfully Installed!**

Start selling your digital products today with this complete e-commerce solution.
EOF

echo "✅ Created comprehensive README"

# Final summary
echo ""
echo "🎉 Setup Complete!"
echo "=================="
echo ""
echo "✅ Created necessary directories"
echo "✅ Imported database schema"
echo "✅ Set up security configurations"
echo "✅ Created test product"
echo "✅ Generated documentation"
echo ""
echo "📋 Next Steps:"
echo "1. Configure payment gateway credentials"
echo "2. Set up email server configuration"
echo "3. Test the complete flow"
echo "4. Start selling your digital products!"
echo ""
echo "📚 Documentation: DIGITAL_PRODUCTS_README.md"
echo "🧪 Test Script: test_digital_products_flow.php"
echo "🔧 Setup Script: setup_digital_products.sh"
echo ""
echo "Happy selling! 🚀"