<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="type" value="biolink" />
    <input type="hidden" name="subtype" value="eshop" />
    <input type="hidden" name="link_id" value="<?= $row->link_id ?>" />

    <div class="notification-container"></div>
	
	<div class="form-product-container">
					
		<div class="d-flex justify-content-between mb-3">
			<div class="flex-grow-1">
				<i class="fas fa-list-alt"></i> <b class="ml-1">Categories</b>
			</div>
			<div>
				<div data-eshop="aci" class="btn btn-primary"><i class="fa fa-plus"></i> Add</div>
			</div>
		</div>
		
		<div class="form-product-main">
			<?php foreach($row->settings as $ky => $sg) {?>				
			<div class="form-product-item">
			
				<div class="form-product-ctg">
					<div class="form-ctg-view">
						<div class="d-flex justify-content-between align-items-center">
							<div data-eshop-btn="true" class="form-ctg-text flex--1"><div class="form-ctg-name"><?= $sg->category ?></div></div>
							<div class="d-flex">
								<div data-eshop="api" class="btn btn-primary cursor-pointer mr-1"><i class="fa fa-plus"></i></div>
								<div class="dropdown">
									<div data-toggle="dropdown" class="btn btn-light cursor-pointer dropdown-toggle dropdown-toggle-simple"><i class="fa fa-ellipsis-v"></i></div>
									<div class="dropdown-menu dropdown-menu-right">
										<a data-eshop="sci" class="dropdown-item none" href="javascript:;"><i class="fas fa-exchange-alt mr-1"></i> <span class="fbv-switch">Show</span></a>
										<a data-eshop="eci" class="dropdown-item none" href="javascript:;"><i class="fa fa-pencil-alt fa-sm mr-1"></i> Edit</a>
										<a data-eshop="dci" class="dropdown-item none" href="javascript:;"><i class="fa fa-times mr-1"></i> Delete</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-ctg-edit">
						<div class="form-group mt-1">
							<input class="form-control" name="category[]" value="<?= $sg->category ?>" placeholder="Insert a category name" required />
						</div>
						<div class="d-flex justify-content-center mb-1">
							<div data-eshop="csp" class="badge badge-primary badge-pill cursor-pointer p-2 pl-4 pr-4">Save</div>
						</div>
					</div>
				</div>
				
				<div class="form-product-bag">
					<?php foreach($sg->products as $pk => $sp) {?>
					<div class="form-bag-item<?= isset($sp->show)&&$sp->show==0 ? ' hidden' : null ?>">
						<div class="form-bag-view">
							
							<div class="form-group">
								<div class="d-flex align-items-stretch">
									<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview mr-2">
										<div class="fbv-image form-image-preview wh-70" style="background-image:url('<?= $sp->image_url?>')"></div>
									</div>
									
									<div class="overflow-hidden w-100 d-flex flex-direction-row flex-wrap">
										<div class="flex--1">
											<div class="fbv-title mb-2"><span><?= $sp->title?></span></div>
											<div class="fbv-description mb-2"<?= !$sp->description ? ' style="display:none"' : '' ?>><?= $sp->description ? $sp->description : '' ?></div>
											<div class="fbv-mprice">
												<div class="text-nowrap"><?= $this->user->currency ? $this->user->currency : "Rp" ?><span class="fbv-price"><?= number_format($sp->price, 0, "", ",") ?></span></div>
												<div class="fbv-pstrike text-nowrap"<?= !$sp->price_strike ? ' style="display:none"' : '' ?>><?= $this->user->currency ? $this->user->currency : "Rp" ?><span class="fbv-price-strike"><?= $sp->price_strike ? number_format($sp->price_strike, 0, "", ",") : '' ?></span></div>
											</div>
										</div>
									</div>
									
									<div class="dropdown">
										<div data-toggle="dropdown" class="btn btn-light  cursor-pointer dropdown-toggle dropdown-toggle-simple"><i class="fa fa-ellipsis-v"></i></div>
										<div class="dropdown-menu dropdown-menu-right">
											<a data-eshop="epi" class="dropdown-item none" href="javascript:;"><i class="fa fa-pencil-alt fa-sm mr-1"></i> Edit</a>
											<a data-eshop="dpi" class="dropdown-item none" href="javascript:;"><i class="fa fa-times mr-1"></i> Delete</a>
										</div>
									</div>
									
								</div>
							</div>
							<hr class="mb-1">
							<div class="form-vrn-main">
								<div class="form-vrn-vrm mb-2">
									<div class="d-flex justify-content-between align-items-center">
										<div class="form-mvrn-text flex--1"><div class="form-vrn-name">Variants</div></div>
										<div class="d-flex">
											<div data-eshop="vra" class="btn btn-primary cursor-pointer mr-1"><i class="fa fa-plus"></i></div>
										</div>
									</div>
								</div>
								
								<?php if(isset($sp->variants)&&$sp->variants) {
								foreach($sp->variants as $vk => $vv) {
								?>
								<div class="form-mvrn-item">
								
									<div class="form-vrn-product">
									
										<div class="form-vrn-view">
											<div class="d-flex justify-content-between align-items-center">
												<div data-eshop="vrb" class="form-vrn-text flex--1"><div class="form-vrn-name"><?= $vv->title ?></div></div>
												<div class="d-flex">
													<label data-eshop="vmr" class="form-radio btn <?= $vv->select ? 'btn-primary' : 'btn-secondary'?> cursor-pointer mr-1">
														<i class="fas fa-check"></i> 
														<input type="radio" class="form-control" role="select" name="select_variant[<?= $ky?>][<?= $pk?>][<?= $vk?>]" value="<?= (int)$vv->select==1 ? 1 : 0?>"<?= (int)$vv->select==1 ? ' checked' : '' ?>/>
													</label>
													<div data-eshop="vma" class="btn btn-primary cursor-pointer mr-1"><i class="fa fa-plus"></i></div>
													<div class="dropdown">
														<div data-toggle="dropdown" class="btn btn-light cursor-pointer dropdown-toggle dropdown-toggle-simple"><i class="fa fa-ellipsis-v"></i></div>
														<div class="dropdown-menu dropdown-menu-right">
															<a data-eshop="vrs" class="dropdown-item none" href="javascript:;"><i class="fas fa-exchange-alt mr-1"></i> <span class="fvr-switch">Hide</span></a>
															<a data-eshop="vre" class="dropdown-item none" href="javascript:;"><i class="fa fa-pencil-alt fa-sm mr-1"></i> Edit</a>
															<a data-eshop="vrd" class="dropdown-item none" href="javascript:;"><i class="fa fa-times mr-1"></i> Delete</a>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-vrn-edit">
											<div class="form-group mt-2">
												<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> Variant Name</label>
												<input type="text" role="variant" class="form-control" name="title_variant[<?= $ky?>][<?= $pk?>][]" value="<?= $vv->title ?>" placeholder="Ex: Color or Size" required/>
											</div>
											<div class="d-flex justify-content-center mt-3 mb-1">
												<div data-eshop="vms" class="badge badge-primary badge-pill cursor-pointer p-2 pl-4 pr-4">Save</div>
											</div>
										</div>
									
									</div>
									
									<div class="form-vrn-bag">
										<?php foreach($vv->variant as $sk => $sv) {?>
										<div class="form-vbag-item">
										
											<div class="form-vbag-view">
												<div class="form-group">
													<div class="d-flex align-items-stretch">
														<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview wh-60 mr-2">
															<div class="fvv-image form-image-preview wh-60" style="background-image:url('<?= $sv->image_url?>')"></div>
														</div>
														
														<div class="overflow-hidden w-100 d-flex flex-direction-row flex-wrap">
															<div class="flex--1">
																<div class="fvv-title mb-2"><span><?= $sv->name?></span></div>
																<div class="fvv-mprice">
																	<div class="text-nowrap"><?= $this->user->currency ? $this->user->currency : "Rp" ?><span class="fvv-price"><?= number_format($sv->price, 0, "", ",") ?></span></div>
																</div>
															</div>
														</div>
														
														<div class="dropdown">
															<div data-toggle="dropdown" class="btn btn-light  cursor-pointer dropdown-toggle dropdown-toggle-simple"><i class="fa fa-ellipsis-v"></i></div>
															<div class="dropdown-menu dropdown-menu-right">
																<a data-eshop="vie" class="dropdown-item none" href="javascript:;"><i class="fa fa-pencil-alt fa-sm mr-1"></i> Edit</a>
																<a data-eshop="vid" class="dropdown-item none" href="javascript:;"><i class="fa fa-times mr-1"></i> Delete</a>
															</div>
														</div>
														
													</div>
												</div>
											</div>
											
											<div class="form-vbag-edit">
												<div class="product-vrn-item">
													<div class="form-group">
														<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> Name</label>
														<input type="text" role="title" class="form-control" name="name_variant[<?= $ky?>][<?= $pk?>][<?= $vk?>][]" value="<?= $sv->name?>" placeholder="Ex: Blue or M" required/>
													</div>
													<div class="form-group">
														<div class="d-flex align-items-stretch">
															<div class="flex-grow-1">
																<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> Image Product</label>
																<div class="custom-file">
																	<input type="file" class="custom-file-input" data-image="upload" name="image_variant[<?= $ky?>][<?= $pk?>][<?= $vk?>][]" accept="image/x-png,image/gif,image/jpeg">
																	<label class="custom-file-label" for="customFile">Choose file</label>
																</div>
															</div>
															<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
																<div role="image" class="form-image-preview wh-70" style="background-image:url('<?= $sv->image_url?>')"></div>
															</div>
														</div>
														<small class="text-danger" data-field="image"></small>
													</div>
													<div class="row">
														<div class="col-lg-6">
															<div class="form-group">
																<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Price</label>
																<input type="number" min="1" role="price" class="form-control" name="price_variant[<?= $ky?>][<?= $pk?>][<?= $vk?>][]" value="<?= $sv->price?>" placeholder="Insert price product" required/>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="form-group">
																<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Weight <small>(Gram)</small></label>
																<input type="number" role="weight" class="form-control" name="weight_variant[<?= $ky?>][<?= $pk?>][<?= $vk?>][]" value="<?= $sv->weight?>" placeholder="Ex: 100" />
															</div>
														</div>
													</div>
													
													<div class="d-flex justify-content-center mb-2">
														<div data-eshop="vns" class="badge badge-primary badge-pill cursor-pointer p-2 pl-4 pr-4">Save</div>
													</div>
												</div>
											</div>
										
										</div>
										<?php }?>
									
									</div>
								
								</div>
								<?php }}?>
							</div>
							
						</div>
						<div class="form-bag-edit">
						
							<div class="form-group">
								<div class="d-flex align-items-stretch">
									<div class="flex-grow-1">
										<label><i class="fas fa-fw fa-image fa-sm mr-1"></i> Image Product</label>
										<div class="custom-file">
											<input type="file" class="custom-file-input" data-image="upload" name="image[<?= $ky?>][]" accept="image/x-png,image/gif,image/jpeg">
											<label class="custom-file-label" for="customFile">Choose file</label>
										</div>
									</div>
									<div class="flex-grow-1 w-100 d-flex align-items-stretch mw-preview ml-2">
										<div role="image" class="form-image-preview wh-70" style="background-image:url(<?= $sp->image_url?>)"></div>
									</div>
								</div>
								<small class="text-danger" data-field="image"></small>
							</div>
							
							<div class="form-group">
								<label><i class="fa fa-fw fa-heading fa-sm mr-1"></i> Title</label>
								<input type="text" role="title" class="form-control" name="title[<?= $ky?>][]" value="<?= $sp->title?>" placeholder="Insert product title" required/>
							</div>
							
							<div class="form-group">
								<label><i class="fa fa-fw fa-paragraph fa-sm mr-1"></i> Description <small>(Opsional)</small></label>
								<textarea class="form-control" role="description" name="description[<?= $ky?>][]" rows="2" placeholder="Insert description product"><?= $sp->description?></textarea>
							</div>
							
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Price</label>
										<input type="number" min="1" role="price" class="form-control" name="price[<?= $ky?>][]" value="<?= $sp->price?>" placeholder="Insert price product" required/>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Price Strike <small>(Opsional)</small></label>
										<input type="number" role="price_strike" class="form-control" name="price_strike[<?= $ky?>][]" value="<?= $sp->price_strike?>" placeholder="Insert price strike product" />
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Weight <small>(Gram)</small></label>
								<input type="number" min="1" role="price" class="form-control" name="weight[<?= $ky?>][]" value="<?= $sp->weight?>" placeholder="Ex: 100" required/>
							</div>
							
							<div class="form-group">
								<label><i class="fa fa-fw fa-calendar fa-sm mr-1"></i> Show Product</label>
								<select class="form-control" role="show" name="show[<?= $ky?>][]">
								<option value="1"<?= isset($sp->show)&&$sp->show==1 ? ' selected' : null?>>Show</option>
								<option value="0"<?= isset($sp->show)&&$sp->show==0 ? ' selected' : null?>>Hide</option>
								</select>
							</div>
							
							<div class="d-flex justify-content-center mb-2">
								<div data-eshop="psi" class="badge badge-primary badge-pill cursor-pointer p-2 pl-4 pr-4">Save</div>
							</div>
						
						</div>
					</div>
					<?php }?>
					
				</div>
				
			</div>
			<?php }?>
		
		</div>
	</div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= $this->language->global->update ?></button>
    </div>
</form>
