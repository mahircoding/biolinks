<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fa fa-credit-card fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Selesaikan Pembayaran Anda</h1>
                        <p class="text-muted">Tinjau pesanan Anda dan lanjutkan dengan pembayaran</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php if($data->product->image): ?>
                                <img src="<?= SITE_URL . 'uploads/products/' . $data->product->image ?>" class="img-fluid rounded mb-3" alt="<?= $data->product->name ?>">
                            <?php endif ?>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <h4><?= $data->product->name ?></h4>
                            <p class="text-muted"><?= $data->product->description ?></p>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>ID Pesanan:</strong>
                                </div>
                                <div class="col-6">
                                    <?= $data->order->order_id ?>
                                </div>
                            </div>
                            
                            <?php if($data->order->customer_name): ?>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Nama:</strong>
                                    </div>
                                    <div class="col-6">
                                        <?= $data->order->customer_name ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Email:</strong>
                                    </div>
                                    <div class="col-6">
                                        <?= $data->order->customer_email ?>
                                    </div>
                                </div>
                            <?php endif ?>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>Total:</strong>
                                </div>
                                <div class="col-6">
                                    <span class="h5 text-primary"><?= format_idr($data->order->amount) ?></span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-warning"><?= ucfirst($data->order->status) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info">
                        <h6><i class="fa fa-fw fa-info-circle"></i> Informasi Pembayaran</h6>
                        <ul class="mb-0">
                            <li>Anda akan mendapat akses instan setelah pembayaran berhasil</li>
                            <li>Email konfirmasi akan dikirim ke alamat email Anda</li>
                            <li>Anda dapat mengakses pembelian Anda kapan saja</li>
                        </ul>
                    </div>
                    
                    <form action="" method="post" class="text-center">
                        <button type="submit" name="pay_now" class="btn btn-primary btn-lg">
                            <i class="fa fa-fw fa-credit-card"></i> Bayar <?= format_idr($data->order->amount) ?>
                        </button>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                Dilindungi oleh Gateway Pembayaran Midtrans
                            </small>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <?php if($this->user): ?>
                            <a href="<?= url('orders') ?>" class="btn btn-outline-secondary">
                                <i class="fa fa-fw fa-arrow-left"></i> Kembali ke Pesanan
                            </a>
                        <?php else: ?>
                            <a href="<?= url('products/catalog') ?>" class="btn btn-outline-secondary">
                                <i class="fa fa-fw fa-arrow-left"></i> Kembali ke Katalog
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>