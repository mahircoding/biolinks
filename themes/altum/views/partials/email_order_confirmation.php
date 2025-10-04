<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - <?= $store_name ?></title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-details h3 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 18px;
        }
        .order-details p {
            margin: 8px 0;
            color: #6c757d;
        }
        .order-details strong {
            color: #495057;
        }
        .product-info {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .product-info:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 80px;
            height: 80px;
            background: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .product-image i {
            font-size: 24px;
            color: #6c757d;
        }
        .product-details {
            flex-grow: 1;
        }
        .product-name {
            font-weight: 600;
            color: #495057;
            margin: 0 0 5px 0;
        }
        .product-price {
            color: #28a745;
            font-weight: 600;
            font-size: 18px;
        }
        .download-section {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .download-section h3 {
            color: #155724;
            margin: 0 0 15px 0;
        }
        .download-button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .download-button:hover {
            background: #218838;
            color: white;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 14px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Order Confirmed!</h1>
            <p>Thank you for your purchase from <?= $store_name ?></p>
        </div>
        
        <div class="content">
            <h2>Hi <?= $customer_name ?>,</h2>
            <p>Your order has been successfully processed and payment completed. Here are your order details:</p>
            
            <div class="order-details">
                <h3>Order Information</h3>
                <p><strong>Order ID:</strong> <?= $order_id ?></p>
                <p><strong>Date:</strong> <?= $order_date ?></p>
                <p><strong>Total Amount:</strong> Rp <?= number_format($amount, 0, ',', '.') ?></p>
                <p><strong>Payment Method:</strong> <?= $payment_method ?></p>
                <p><strong>Status:</strong> <span style="color: #28a745; font-weight: 600;">Completed</span></p>
            </div>
            
            <h3>Product Details</h3>
            <div class="product-info">
                <div class="product-image">
                    <i class="fa fa-file"></i>
                </div>
                <div class="product-details">
                    <div class="product-name"><?= $product_name ?></div>
                    <div class="product-price">Rp <?= number_format($product_price, 0, ',', '.') ?></div>
                </div>
            </div>
            
            <div class="download-section">
                <h3>📥 Download Your Product</h3>
                <p>Your digital product is ready for download. Click the button below to access your purchase:</p>
                <a href="<?= $download_link ?>" class="download-button" target="_blank">
                    Download Now
                </a>
                <p style="margin-top: 15px; font-size: 14px; color: #6c757d;">
                    <strong>Note:</strong> This download link will expire in 30 days for security purposes.
                </p>
            </div>
            
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <h4 style="color: #856404; margin: 0 0 10px 0;">📋 Important Information</h4>
                <ul style="margin: 0; padding-left: 20px; color: #856404;">
                    <li>Check your spam/junk folder if you don't see this email</li>
                    <li>Save this email for future reference</li>
                    <li>Download your product as soon as possible</li>
                    <li>For any issues, contact seller support</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= $store_url ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
                    Visit <?= $store_name ?> Store →
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>&copy; <?= date('Y') ?> <?= $store_name ?>. All rights reserved.</p>
            <p>
                <a href="<?= $store_url ?>"><?= $store_url ?></a> | 
                <a href="mailto:<?= $store_email ?>"><?= $store_email ?></a>
            </p>
        </div>
    </div>
</body>
</html>