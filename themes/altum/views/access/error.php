<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <!-- Error Header -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="fa fa-exclamation-triangle fa-5x text-warning"></i>
                </div>
                <h1 class="h3 text-warning mb-3">Akses Tidak Valid</h1>
                <p class="text-muted"><?= $data->error ?></p>
            </div>

            <!-- Error Card -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <h5 class="mb-0">
                        <i class="fa fa-lock fa-fw"></i> Link Akses Bermasalah
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <h6 class="text-dark mb-3">Kemungkinan Penyebab:</h6>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-2">
                                <i class="fa fa-times-circle text-danger fa-fw"></i> 
                                Link akses sudah kedaluwarsa
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-times-circle text-danger fa-fw"></i> 
                                Link tidak valid atau rusak
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-times-circle text-danger fa-fw"></i> 
                                Produk belum dibeli atau pembayaran belum selesai
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-dark mb-3">Solusi:</h6>
                        <p class="text-muted">
                            Silakan gunakan email verifikasi untuk mengakses produk yang telah Anda beli, 
                            atau hubungi customer service jika masalah berlanjut.
                        </p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <a href="<?= url('products/catalog') ?>" class="btn btn-primary">
                                <i class="fa fa-shopping-bag fa-fw"></i> Lihat Katalog
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= url() ?>" class="btn btn-outline-secondary">
                                <i class="fa fa-home fa-fw"></i> Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card border-0 bg-light mt-4">
                <div class="card-body text-center p-4">
                    <h6 class="text-dark mb-3">
                        <i class="fa fa-life-ring fa-fw"></i> Butuh Bantuan Lebih Lanjut?
                    </h6>
                    <p class="text-muted mb-3">
                        Jika Anda yakin telah membeli produk dan memiliki bukti pembayaran, 
                        silakan hubungi customer service dengan menyertakan:
                    </p>
                    <ul class="list-unstyled text-muted small">
                        <li><i class="fa fa-check fa-fw text-success"></i> Email yang digunakan saat pembelian</li>
                        <li><i class="fa fa-check fa-fw text-success"></i> Order ID atau Transaction ID</li>
                        <li><i class="fa fa-check fa-fw text-success"></i> Screenshot bukti pembayaran</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>