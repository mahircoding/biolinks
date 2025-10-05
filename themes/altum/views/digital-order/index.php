<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex">
    <h1 class="h3">Digital Orders</h1>
  </div>

<?php display_notifications() ?>

<div class="table-responsive mt-4">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Pembeli</th>
                <th>Status</th>
                <th>Channel</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach(($data->orders ?? []) as $order): ?>
            <tr>
                <td><?= (int)$order->order_id ?></td>
                <td><?= $order->product_name ?></td>
                <td><?= $order->buyer_name ?><br><small><?= $order->buyer_email ?></small></td>
                <td><?= strtoupper($order->status) ?></td>
                <td><?= $order->payment_channel ?: '-' ?></td>
                <td><?= $order->created_at ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>


