<?php
// Penjelasan mengapa menggunakan price_cents / 100
echo "=== PENJELASAN PRICE_CENTS / 100 ===\n\n";

echo "1. DATA DI DATABASE (price_cents):\n";
$examples = [
    ['price_cents' => 50000, 'description' => 'Produk A - Rp 50.000'],
    ['price_cents' => 100000, 'description' => 'Produk B - Rp 100.000'],
    ['price_cents' => 1500000, 'description' => 'Produk C - Rp 1.500.000'],
    ['price_cents' => 2500000, 'description' => 'Produk D - Rp 2.500.000']
];

foreach($examples as $example) {
    echo "   - price_cents: " . number_format($example['price_cents']) . " → " . $example['description'] . "\n";
}

echo "\n2. KONVERSI UNTUK TAMPILAN:\n";
foreach($examples as $example) {
    $price_rupiah = $example['price_cents'] / 100;
    $formatted = "Rp " . number_format($price_rupiah, 0, ',', '.');
    echo "   - " . number_format($example['price_cents']) . " ÷ 100 = " . number_format($price_rupiah) . " → " . $formatted . "\n";
}

echo "\n3. MENGAPA TIDAK LANGSUNG SIMPAN DALAM RUPIAH?\n";
echo "   ❌ Jika disimpan: 50000 (rupiah)\n";
echo "   ❌ Masalah: Bagaimana dengan desimal? 50000.50?\n";
echo "   ❌ Floating point error: 0.1 + 0.2 = 0.30000000000000004\n";
echo "   ❌ Inconsistent: Beberapa sistem pakai desimal, beberapa tidak\n\n";

echo "   ✅ Jika disimpan: 5000000 (sen)\n";
echo "   ✅ Selalu integer, tidak ada desimal\n";
echo "   ✅ Presisi sempurna: 5000000 sen = 50000.00 rupiah\n";
echo "   ✅ Konsisten: Semua mata uang pakai satuan terkecil\n\n";

echo "4. CONTOH PERHITUNGAN:\n";
$price_cents = 50000;
echo "   price_cents = " . number_format($price_cents) . "\n";
echo "   price_rupiah = " . number_format($price_cents) . " ÷ 100 = " . number_format($price_cents / 100) . "\n";
echo "   Tampilan = Rp " . number_format($price_cents / 100, 0, ',', '.') . "\n\n";

echo "5. JIKA TIDAK PAKAI / 100:\n";
echo "   ❌ 50000 (sen) ditampilkan sebagai Rp 50.000 (SALAH!)\n";
echo "   ✅ 50000 (sen) ÷ 100 = 500 (rupiah) ditampilkan sebagai Rp 500 (BENAR!)\n\n";

echo "=== KESIMPULAN ===\n";
echo "price_cents / 100 diperlukan untuk mengkonversi dari satuan sen ke rupiah\n";
echo "sebelum ditampilkan ke user.\n";
?>
