<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Success Header -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="fa fa-check-circle fa-5x text-success"></i>
                </div>
                <h1 class="h2 text-success mb-3">Akses Produk Berhasil!</h1>
                <p class="text-muted">Selamat! Anda dapat mengakses produk yang telah dibeli.</p>
            </div>

            <!-- Product Access Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-success text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fa fa-download fa-fw"></i> <?= $data->product->name ?>
                    </h4>
                </div>
                <div class="card-body p-4">
                    <?php if($data->product->image): ?>
                        <div class="text-center mb-4">
                            <img src="<?= SITE_URL . UPLOADS_URL_PATH . $data->product->image ?>" 
                                 alt="<?= $data->product->name ?>" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="max-height: 200px; object-fit: cover;">
                        </div>
                    <?php endif ?>

                    <!-- Product Description -->
                    <?php if($data->product->description): ?>
                        <div class="mb-4">
                            <h5 class="text-dark mb-3">Deskripsi Produk:</h5>
                            <div class="text-muted">
                                <?= nl2br(htmlspecialchars($data->product->description)) ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <!-- Order Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="text-dark mb-2">
                                    <i class="fa fa-receipt fa-fw"></i> Informasi Pembelian
                                </h6>
                                <small class="text-muted d-block">Order ID: <?= $data->order->order_id ?></small>
                                <small class="text-muted d-block">Email: <?= $data->order->customer_email ?></small>
                                <small class="text-muted d-block">Tanggal: <?= date('d M Y H:i', strtotime($data->order->completed_datetime)) ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="text-dark mb-2">
                                    <i class="fa fa-user fa-fw"></i> Data Pembeli
                                </h6>
                                <small class="text-muted d-block">Nama: <?= $data->order->customer_name ?></small>
                                <?php if($data->order->customer_phone): ?>
                                    <small class="text-muted d-block">Telepon: <?= $data->order->customer_phone ?></small>
                                <?php endif ?>
                                <small class="text-muted d-block">Total: <?= format_idr($data->order->amount) ?></small>
                            </div>
                        </div>
                    </div>

                    <!-- Access Button -->
                    <?php if($data->product->digital_link): ?>
                        <div class="text-center">
                            <a href="<?= $data->product->digital_link ?>" 
                               class="btn btn-success btn-lg px-5 py-3 shadow-sm" 
                               target="_blank"
                               style="border-radius: 50px;">
                                <i class="fa fa-download fa-fw"></i> Akses Produk Sekarang
                            </a>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle fa-fw"></i> 
                                    Link akan terbuka di tab baru. Simpan link ini untuk akses di kemudian hari.
                                </small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            <i class="fa fa-exclamation-triangle fa-fw"></i>
                            Link akses produk belum tersedia. Silakan hubungi penjual.
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card border-0 bg-light">
                <div class="card-body text-center p-4">
                    <h6 class="text-dark mb-3">
                        <i class="fa fa-bookmark fa-fw"></i> Simpan Halaman Ini
                    </h6>
                    <p class="text-muted mb-3">
                        Bookmark halaman ini untuk akses mudah ke produk Anda di kemudian hari. 
                        Link akses ini bersifat permanen dan dapat digunakan kapan saja.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-print fa-fw"></i> Print
                            </button>
                        </div>
                        <div class="col-auto">
                            <button onclick="navigator.share ? navigator.share({title: '<?= $data->product->name ?>', url: window.location.href}) : copyToClipboard(window.location.href)" 
                                    class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-share fa-fw"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link berhasil disalin ke clipboard!');
    });
}
</script>