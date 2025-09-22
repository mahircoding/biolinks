<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="fa fa-envelope-open fa-4x text-primary"></i>
                </div>
                <h1 class="h3 mb-3">Verifikasi Email</h1>
                <p class="text-muted">Masukkan email yang digunakan saat membeli produk untuk mengakses konten.</p>
            </div>

            <!-- Product Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <?php if($data->product->image): ?>
                            <div class="col-auto">
                                <img src="<?= SITE_URL . UPLOADS_URL_PATH . $data->product->image ?>" 
                                     alt="<?= $data->product->name ?>" 
                                     class="rounded shadow-sm" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                        <?php endif ?>
                        <div class="col">
                            <h5 class="mb-1"><?= $data->product->name ?></h5>
                            <div class="text-muted"><?= format_idr($data->product->price) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Form -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h5 class="mb-0">
                        <i class="fa fa-key fa-fw"></i> Akses Produk
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle fa-fw"></i>
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php unset($_SESSION['error']) ?>
                    <?php endif ?>

                    <form action="<?= url('access/verify') ?>" method="post">
                        <input type="hidden" name="product_id" value="<?= $data->product->product_id ?>">
                        
                        <div class="form-group mb-4">
                            <label for="email" class="form-label font-weight-bold">
                                <i class="fa fa-envelope fa-fw"></i> Email Address
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control form-control-lg" 
                                   placeholder="Masukkan email yang digunakan saat pembelian"
                                   required
                                   style="border-radius: 10px; padding: 15px;">
                            <small class="form-text text-muted">
                                Gunakan email yang sama dengan saat melakukan pembelian produk ini.
                            </small>
                        </div>

                        <button type="submit" 
                                class="btn btn-primary btn-lg btn-block shadow-sm"
                                style="border-radius: 50px; padding: 15px;">
                            <i class="fa fa-unlock fa-fw"></i> Verifikasi & Akses Produk
                        </button>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card border-0 bg-light mt-4">
                <div class="card-body text-center p-4">
                    <h6 class="text-dark mb-3">
                        <i class="fa fa-question-circle fa-fw"></i> Butuh Bantuan?
                    </h6>
                    <p class="text-muted mb-3">
                        Jika Anda mengalami kesulitan mengakses produk yang telah dibeli, 
                        pastikan menggunakan email yang benar atau hubungi customer service.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <a href="<?= url('products/catalog') ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-arrow-left fa-fw"></i> Kembali ke Katalog
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= url('product/' . $data->product->product_id) ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-eye fa-fw"></i> Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>