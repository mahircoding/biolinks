<?php
// Test new price format (without /100)
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TEST FORMAT HARGA BARU (TANPA /100) ===\n\n";

// Test different price values
$test_prices = [50000, 100000, 1500000, 2500000];

echo "Testing price formatting:\n";
echo "price_cents (DB) | Tampilan (Rp) | Format Lama (/100) | Format Baru (langsung)\n";
echo "------------------|---------------|-------------------|----------------------\n";

foreach($test_prices as $price_cents) {
    $format_lama = 'Rp ' . number_format($price_cents / 100, 0, ',', '.');
    $format_baru = 'Rp ' . number_format($price_cents, 0, ',', '.');
    
    echo sprintf("%-17s | %-13s | %-17s | %s\n", 
        number_format($price_cents), 
        'Rp ' . number_format($price_cents, 0, ',', '.'),
        $format_lama, 
        $format_baru
    );
}

echo "\n=== CONTOH DATA ===\n";
echo "Database: price_cents = 50000\n";
echo "Tampilan: Rp " . number_format(50000, 0, ',', '.') . "\n";
echo "Artinya: Rp 50.000 (lima puluh ribu rupiah)\n\n";

echo "Database: price_cents = 100000\n";
echo "Tampilan: Rp " . number_format(100000, 0, ',', '.') . "\n";
echo "Artinya: Rp 100.000 (seratus ribu rupiah)\n\n";

echo "=== KESIMPULAN ===\n";
echo "✅ Format baru: price_cents langsung ditampilkan tanpa dibagi 100\n";
echo "✅ Jika price_cents = 50000, tampilan = Rp 50.000\n";
echo "✅ Jika price_cents = 100000, tampilan = Rp 100.000\n";
?>
