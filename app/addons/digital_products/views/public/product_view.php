<h2><?= $product->name ?></h2>
<p>Harga: Rp <?= number_format($product->price, 0, ',', '.') ?></p>

<form method="POST" action="<?= url('p/checkout/'.$product->product_id) ?>">
    <input type="text" name="name" placeholder="Nama Anda" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Beli Sekarang</button>
</form>
