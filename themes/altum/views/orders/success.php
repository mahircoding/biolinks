<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fa fa-check-circle fa-4x text-success mb-3"></i>
                        <h1 class="h3 text-success">Pembayaran Berhasil!</h1>
                        <p class="text-muted">Terima kasih atas pembelian Anda. Pesanan Anda telah selesai diproses.</p>
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5>Detail Pesanan</h5>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>ID Pesanan:</strong></div>
                                <div class="col-8"><?= $data->order->order_id ?></div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>Produk:</strong></div>
                                <div class="col-8"><?= $data->product->name ?></div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>Total:</strong></div>
                                <div class="col-8"><?= format_idr($data->order->amount) ?></div>
                            </div>
                            
                            <?php if($data->order->customer_name): ?>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Nama Customer:</strong></div>
                                    <div class="col-8"><?= $data->order->customer_name ?></div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Email:</strong></div>
                                    <div class="col-8"><?= $data->order->customer_email ?></div>
                                </div>
                                
                                <?php if($data->order->customer_phone): ?>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>No. HP:</strong></div>
                                        <div class="col-8"><?= $data->order->customer_phone ?></div>
                                    </div>
                                <?php endif ?>
                            <?php endif ?>
                            
                            <div class="row mb-2">
                                <div class="col-4"><strong>Tanggal Pembelian:</strong></div>
                                <div class="col-8"><?= \Altum\Date::get($data->order->completed_datetime ?? $data->order->datetime, 1) ?></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-4"><strong>Status:</strong></div>
                                <div class="col-8">
                                    <span class="badge badge-success">Selesai</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($data->product->digital_link): ?>
                        <div class="alert alert-success mt-4">
                            <h6><i class="fa fa-fw fa-download"></i> Akses Produk Anda</h6>
                            <p class="mb-2">Produk digital Anda siap diakses:</p>
                            <a href="<?= $data->product->digital_link ?>" class="btn btn-success" target="_blank">
                                <i class="fa fa-fw fa-external-link-alt"></i> Akses Produk
                            </a>
                        </div>
                    <?php endif ?>
                    
                    <div class="alert alert-info">
                        <h6><i class="fa fa-fw fa-envelope"></i> Konfirmasi Email</h6>
                        <p class="mb-0">
                            Email konfirmasi dengan detail pesanan dan informasi akses telah dikirim ke alamat email Anda.
                        </p>
                    </div>
                    
                    <div class="text-center mt-4">
                        <?php if($this->user): ?>
                            <a href="<?= url('orders') ?>" class="btn btn-primary">
                                <i class="fa fa-fw fa-list"></i> Lihat Semua Pesanan
                            </a>
                        <?php endif ?>
                        
                        <a href="<?= url('products/catalog') ?>" class="btn btn-outline-primary <?= $this->user ? 'ml-2' : '' ?>">
                            <i class="fa fa-fw fa-shopping-cart"></i> Jelajahi Produk Lainnya
                        </a>
                    </div>
                    
                    <hr class="mt-4">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            Butuh bantuan? Hubungi tim support kami di support@<?= parse_url(SITE_URL, PHP_URL_HOST) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>