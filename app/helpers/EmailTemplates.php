<?php

namespace Altum\Helpers;

class EmailTemplates {
    
    public static function getPurchaseSuccessTemplate($order, $product, $store_user) {
        $subject = 'Pembelian Berhasil - ' . $product->title;
        
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #28a745; margin: 0;'>Pembelian Berhasil!</h1>
            </div>
            
            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <h2 style='color: #333; margin-top: 0;'>Halo {$order->customer_name},</h2>
                <p style='color: #666; line-height: 1.6;'>
                    Terima kasih atas pembelian Anda! Pembayaran telah berhasil diproses dan produk digital Anda sudah siap untuk diakses.
                </p>
            </div>
            
            <div style='background: #fff; border: 2px solid #28a745; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <h3 style='color: #28a745; margin-top: 0;'>Detail Pesanan</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>ID Transaksi:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>{$order->transaction_id}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Produk:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>{$product->title}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Harga:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>Rp " . number_format($order->total_amount, 0, ',', '.') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Tanggal:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>" . date('d/m/Y H:i', strtotime($order->datetime)) . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0;'><strong>Penjual:</strong></td>
                        <td style='padding: 8px 0; text-align: right;'>{$store_user->name}</td>
                    </tr>
                </table>
            </div>
            
            <div style='background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 25px; border-radius: 8px; margin: 20px 0; text-align: center;'>
                <h3 style='margin-top: 0; color: white;'>🎉 Akses Produk Digital Anda</h3>
                <p style='margin: 15px 0; opacity: 0.9;'>
                    Klik tombol di bawah untuk mengakses produk digital yang telah Anda beli:
                </p>
                <a href='{$product->url}' style='display: inline-block; background: white; color: #28a745; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; margin: 10px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.2);'>
                    🚀 Akses Produk Sekarang
                </a>
                <p style='margin: 15px 0 0 0; font-size: 14px; opacity: 0.8;'>
                    Link ini akan tetap aktif dan dapat diakses kapan saja.
                </p>
            </div>
            
            <div style='background: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <h4 style='color: #1976d2; margin-top: 0;'>📋 Informasi Penting:</h4>
                <ul style='color: #666; line-height: 1.6; margin: 0; padding-left: 20px;'>
                    <li>Simpan email ini sebagai bukti pembelian</li>
                    <li>Link akses produk tidak akan expired</li>
                    <li>Jika mengalami kesulitan, hubungi penjual melalui WhatsApp: {$order->customer_whatsapp}</li>
                </ul>
            </div>
            
            <div style='text-align: center; margin: 30px 0; padding: 20px; border-top: 2px solid #eee;'>
                <p style='color: #666; margin: 0;'>
                    Terima kasih telah berbelanja! Jika ada pertanyaan, jangan ragu untuk menghubungi kami.
                </p>
                <p style='color: #999; font-size: 12px; margin: 10px 0 0 0;'>
                    Email ini dikirim otomatis, mohon tidak membalas email ini.
                </p>
            </div>
        </div>
        ";
        
        return (object) [
            'subject' => $subject,
            'body' => $body
        ];
    }
    
    public static function getOrderNotificationTemplate($order, $product, $customer_email) {
        $subject = 'Pesanan Baru - ' . $product->title;
        
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #007bff; margin: 0;'>Pesanan Baru Masuk!</h1>
            </div>
            
            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <p style='color: #666; line-height: 1.6;'>
                    Anda mendapat pesanan baru untuk produk digital Anda. Berikut detail pesanannya:
                </p>
            </div>
            
            <div style='background: #fff; border: 2px solid #007bff; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <h3 style='color: #007bff; margin-top: 0;'>Detail Pesanan</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>ID Transaksi:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>{$order->transaction_id}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Produk:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>{$product->title}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Harga:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>Rp " . number_format($order->total_amount, 0, ',', '.') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Pembeli:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>{$order->customer_name}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Email:</strong></td>
                        <td style='padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;'>{$order->customer_email}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0;'><strong>WhatsApp:</strong></td>
                        <td style='padding: 8px 0; text-align: right;'>{$order->customer_whatsapp}</td>
                    </tr>
                </table>
            </div>
            
            <div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;'>
                <h4 style='color: #155724; margin-top: 0;'>✅ Pembayaran Berhasil</h4>
                <p style='color: #155724; margin: 0;'>
                    Pembayaran telah dikonfirmasi dan email akses produk sudah dikirim ke pembeli.
                </p>
            </div>
        </div>
        ";
        
        return (object) [
            'subject' => $subject,
            'body' => $body
        ];
    }
}