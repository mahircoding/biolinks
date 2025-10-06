<?php
// Test price format consistency
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing price format consistency...\n";

// Test different price values
$test_prices = [50000, 100000, 1500000, 2500000];

echo "\nTesting price formatting:\n";
echo "Price (cents) | Expected (Rupiah) | Current Format\n";
echo "--------------|-------------------|----------------\n";

foreach($test_prices as $price_cents) {
    $expected = 'Rp ' . number_format($price_cents / 100, 0, ',', '.');
    $current_format = 'Rp ' . number_format($price_cents / 100, 0, ',', '.');
    
    echo sprintf("%-13s | %-17s | %s\n", 
        number_format($price_cents), 
        $expected, 
        $current_format
    );
}

echo "\nTesting different number_format parameters:\n";
$price = 50000;
echo "price_cents: $price\n";
echo "price_cents / 100: " . ($price / 100) . "\n";
echo "number_format(price_cents / 100, 0, ',', '.'): " . number_format($price / 100, 0, ',', '.') . "\n";
echo "number_format(price_cents, 0, ',', '.'): " . number_format($price, 0, ',', '.') . "\n";

echo "\nTest completed.\n";
?>
