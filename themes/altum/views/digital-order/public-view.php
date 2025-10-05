<?php defined('ALTUMCODE') || die() ?>

<?php $product = $this->view_content['product']; ?>

<div class="container d-flex justify-content-center">
    <div class="col-md-8">
        <h1 class="h3 mb-3"><?= $product->name ?></h1>
        <?php if(!empty($product->description)): ?>
            <p><?= nl2br($product->description) ?></p>
        <?php endif ?>
        <div class="mb-4">
            <strong>Harga:</strong> <?= number_format($product->price_cents / 100, 2) . ' ' . $product->currency ?>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Beli Tanpa Akun</h2>
                <form action="<?= url('digital-order/checkout') ?>" method="post">
                    <input type="hidden" name="slug" value="<?= $product->slug ?>" />
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>No WhatsApp</label>
                        <input type="text" name="phone" class="form-control" />
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-block">Buat Pesanan & Kirim Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


