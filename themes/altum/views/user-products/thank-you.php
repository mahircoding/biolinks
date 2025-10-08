<?php defined('ALTUMCODE') || die() ?>

<?php
/* Facebook Pixel tracking for purchase completion */
$pixel_id = $data->user->facebook_pixel_id ?? null;
if ($pixel_id) {
    echo \Altum\Helpers\FacebookPixel::get_base_code($pixel_id);
    echo \Altum\Helpers\FacebookPixel::track_purchase($data->order, $data->product);
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h1 class="h3 mb-3">Terima kasih!</h1>
            <p>Kami telah mengirimkan link akses produk ke email: <strong><?= $data->email ?></strong>.</p>
            <p>Jika belum menerima, periksa folder spam/promosi.</p>
        </div>
    </div>
</div>
