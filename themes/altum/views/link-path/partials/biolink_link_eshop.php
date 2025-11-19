<?php defined('ALTUMCODE') || die() ?>
<?php 
// Get settings - handle both old and new format
$settings_data = is_object($data->link->settings) ? $data->link->settings : json_decode(json_encode($data->link->settings));
$products_data = isset($settings_data->data) ? $settings_data->data : $settings_data;
$button_text = isset($settings_data->button_text) ? $settings_data->button_text : 'Add to Cart';
?>

<div class="my-3 category-product">
    <div class="form-group">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text text-info">Category</span>
			</div>
			<select class="selectpicker">
			<option value="all">All</option>
			<?php foreach($products_data as $iy => $it) {?>
			<option value="<?= $iy ?>"><?= $it->category ?></option>
			<?php }?>
			</select>
		</div>
	</div>
	<div class="pricing-table">
		<div class="row justify-content-center align-items-stretch">
		<?php $num_prd=0; foreach($products_data as $iy => $it) {?>
		<?php foreach($it->products as $iz => $pr) {
		if(isset($pr->show)&&$pr->show) {
		?>
		<div data-filter-product="<?= $iy ?>" class="col-md-4 col-lg-4 mb-4 cat-product">
			<div class="item">
				<img class="image" src="<?= str_replace('http://','https://',$pr->image_url) ?>" />
				<div class="product-desc">
					<h3 class="title"><?= $pr->title ?></h3>
					<div class="desc"><?= isset($pr->description) ? $pr->description : '' ?></div>
					<div class="price"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></div>
					<?php if(!empty($pr->price_strike)) {?>
					<div class="price-strike"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></div>
					<?php }?>
				</div>
				<div class="product-btn">
					<a class="none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
				</div>
			</div>
		</div>
		<?php } elseif(!isset($pr->show)) {?>
		<div data-filter-product="<?= $iy ?>" class="col-md-4 col-lg-4 mb-4 cat-product">
			<div class="item">
				<img class="image" src="<?= str_replace('http://','https://',$pr->image_url) ?>" />
				<div class="product-desc">
					<h3 class="title"><?= $pr->title ?></h3>
					<div class="desc"><?= isset($pr->description) ? $pr->description : '' ?></div>
					<div class="price"><?= $data->user->currency.number_format($pr->price,0,"",",") ?></div>
					<?php if(!empty($pr->price_strike)) {?>
					<div class="price-strike"><?= $data->user->currency.number_format($pr->price_strike,0,"",",") ?></div>
					<?php }?>
				</div>
				<div class="product-btn">
					<a class="none" data-index="<?= $num_prd ?>" data-cart="add" data-link-id="<?= $data->link->link_id.':'.$iy.":".$iz ?>" href="javascript:;"><?= $button_text ?></a>
				</div>
			</div>
		</div>
		<?php }
		if(empty($pr->link)) $num_prd++;
		}}
		?>
		</div>
	</div>
	<script>eshop[<?= $data->link->link_id ?>] = <?= json_encode($products_data) ?></script>
	<style>
	.bootstrap-select{flex: 1 1 auto !important;}.bootstrap-select .btn{line-height:2.25 !important;border-top-left-radius:0;border-bottom-left-radius:0;}
	</style>
</div>

