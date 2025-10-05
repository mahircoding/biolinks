<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="h4 mb-3">Checkout - <?= $data->product->name ?></h2>
                    <p class="text-muted mb-3">Oleh: <?= $data->user->name ?></p>
                    <div class="mb-4">
                        <strong class="h5 text-primary"><?= number_format($data->product->price_cents / 100, 2) . ' ' . $data->product->currency ?></strong>
                    </div>
                    
                    <form action="" method="post">
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
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select name="method" class="form-control">
                                <option value="QRIS">QRIS</option>
                                <option value="BCAVA">BCA Virtual Account</option>
                                <option value="BNIVA">BNI Virtual Account</option>
                                <option value="BRIVA">BRI Virtual Account</option>
                                <option value="MANDIRIVA">Mandiri Virtual Account</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-block">Proses Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
