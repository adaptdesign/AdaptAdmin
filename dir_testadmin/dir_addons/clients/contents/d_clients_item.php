<?php

class d_clients_item extends global_clients {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
	//=========================================
	public function p_show_item() {
    if(!empty($this->content_data_id)) { $this->set_content_data("",$this->content_data_id); }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if(empty($this->content_data)) { $this->fail_page[] = "Unable to load content data"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page); return; } //==============
		?>
		<div class="row group_details">
			<div class="col-md-5 col-lg-4 col-xl-3">
				<div style="padding: 8px;">
					<div class="row">
						<div class="col-md-12">
              <a class="do_ajax pull-right btn btn-sm btn-default" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_clients_addedit")?>&action=<?=encode("p_edit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode(e_a($this->content_data,'content_id'))?>"><i class="fa fa-pencil"></i></a>
							<div class="section_menu_title" style="border-bottom: 0;padding-bottom: 0;margin-bottom: 0;"><i class="fa fa-<?=$this->widget_data['font_icon']?> text-themed"></i> <?=e_a($this->content_data,'title')?></div>
						</div>
					</div>
				</div>

				<div style="padding: 0px 8px 8px;">
					<div class="tabs-container">
						<div role="tabpanel">
							<!-- Tab panes -->
							<div class="tab-content" style="border:1px solid #ddd;">
								<div role="tabpanel" class="tab-pane fade in active" id="info_img">
									<? if(!empty(e_a($this->content_data,'u_link')) && file_exists(URL_ROOT."/".$this->content_data['u_link'])) { ?>
										<img src="<?=URL_PATH.$this->content_data['u_link']?>" style="width:100%;">
									<? } else { ?>
										<img src="<?=URL_PATH."dir_img/client_logo.png"?>" style="width:100%;">
									<? } ?>
									<div class="clearfix"></div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="info_map">
									<div style="overflow: hidden;position: relative;padding-top: 100%;width: 100%;">
										<div id="gmap_canvas" style="position: absolute;overflow: hidden;left: 0;top: 0;right: 0;bottom: 0;"></div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="icon_widgets">
					<ul class="nav nav-tabs pull-right nav-unselect" role="tablist" style="padding: 0px 8px 8px;">
						<? $i=0; if(!empty($cont_icon_widg)) { foreach($cont_icon_widg as $row){ $i++; ?>
							<li <? if($i == 1) { echo "class='active'"; } ?> role="presentation"><a href="#<?=$row['pl_title']?>_ci<?=e_a($this->content_data,'content_id')?>" aria-controls="<?=$row['pl_title']?>_ci<?=e_a($this->content_data,'content_id')?>" role="tab" data-toggle="tab" title="<?=$row['title']?>">
								<i class="fa fa-<?=$row['font_icon']?>"></i><span class='notif-<?=$row['pl_title']?>-<?=$this->content_data['content_id']?> label label-warning'></span>
							</a></li>
						<? } } ?>
					</ul>
					<ul class="nav nav-tabs pull-left nav-unselect" role="tablist" style="padding: 0px 8px 8px;">
						<li class="active" role="presentation"><a href="#info_img" aria-controls="info_img" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i></a></li>
						<li role="presentation"><a href="#info_map" aria-controls="info_map" role="tab" data-toggle="tab"><i class="fa fa-map-o"></i></a></li>
					</ul>

					<div class="clearfix"></div>

					<div class="tab-content">
						<? $i=0; if(!empty($cont_icon_widg)) { foreach($cont_icon_widg as $row){ $i++; ?>
							<div role="tabpanel" class="tab-pane fade <? if($i == 1) { echo "in active"; } ?>" id="<?=$row['pl_title']?>_ci<?=$this->content_data['content_id']?>">
								<?
								$wigclass = new $row['pl_title']($this);
								$wigclass->icon_widget($row,"-".$plugin_data['plugin_id'].",".$this->content_data['content_id']."-");
								?>
							<div class="clearfix"></div></div>
						<? } } ?>
					</div>
				</div>
			</div>
			<div class="col-md-7 col-lg-8 col-xl-9">
				<div class="tabs-container">
					<div role="tabpanel">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li class="active" role="presentation"><a href="#details_<?=$this->content_data['content_id']?>" aria-controls="details_<?=$this->content_data['content_id']?>" role="tab" data-toggle="tab"><i class="fa fa-info text-themed"></i> Details<span class='notif-<?=$row['pl_title']?>-<?=$this->content_data['content_id']?> label label-warning'></span></a></li>
							<? $i=0; if(!empty($cont_page_widg)) { foreach($cont_page_widg as $row){ $i++; ?>
								<li role="presentation"><a href="#<?=$row['pl_title']?>_cp<?=$this->content_data['content_id']?>" aria-controls="<?=$row['pl_title']?>_cp<?=$this->content_data['content_id']?>" role="tab" data-toggle="tab"><i class="fa fa-<?=$row['font_icon']?> text-themed"></i> <?=$row['title']?><span class='notif-<?=$row['pl_title']?>-<?=$this->content_data['content_id']?> label label-warning'></span></a></li>
							<? } } ?>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content tab-content-bordered">
							<div role="tabpanel" class="tab-pane fade in active" id="details_<?=$this->content_data['content_id']?>">
								<div class="group_tabselector">
									<div class="mail-box-header">

									</div>
									<div class="clearfix"></div>
									<div class="mail-box">
										<div class="grid-option-label m-b-sm"><i class="fa fa-angle-double-right text-themed"></i> Details</div>
										<div class="row m-b-md">
											<div class="col-md-7">
												<div class="row m-b-xs">
													<div class="col-md-6">
														<label>Name</label>
														<div class="grid-input-display"><?=$this->content_data['title']?></div>
													</div>
													<div class="col-md-6">
														<label>Status</label>
														<? if(!empty($this->content_data['clientstatuscolor'])) { $clientstatuscolor = "background:".$this->content_data['clientstatuscolor'].";"; } else { "background:".$clientstatuscolor = "#23b7e5".";"; } ?>
														<div class="grid-input-display" style="color:#FFF;<?=$clientstatuscolor?>"><?=$this->content_data['clientstatus']?></div>
													</div>
												</div>
												<div class="row m-b-xs">
													<div class="col-md-6">
														<label>Phone</label>
														<div class="grid-input-display"><?=$this->content_data['phone']?></div>
													</div>
													<div class="col-md-6">
														<label>Email</label>
														<div class="grid-input-display"><?=$this->content_data['email']?></div>
													</div>
												</div>
												<div class="row m-b-xs">
													<div class="col-md-6">
														<label>Cell</label>
														<div class="grid-input-display"><?=$this->content_data['cell']?></div>
													</div>
													<div class="col-md-6">
														<label>Fax</label>
														<div class="grid-input-display"><?=$this->content_data['fax']?></div>
													</div>
												</div>
												<div class="row m-b-xs">
													<div class="col-md-6">
														<label>Address</label>
														<div class="grid-input-display"><?=$this->content_data['address']?></div>
													</div>
													<div class="col-md-6">
														<label>City</label>
														<div class="grid-input-display"><?=$this->content_data['city']?></div>
													</div>
												</div>
												<div class="row m-b-xs">
													<div class="col-md-6">
														<label>State</label>
														<div class="grid-input-display"><?=$this->content_data['state']?></div>
													</div>
													<div class="col-md-6">
														<label>Zip</label>
														<div class="grid-input-display"><?=$this->content_data['zip']?></div>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<label>Description</label>
												<div class="grid-input-display" style="min-height: 255px;height: auto;"><?=$this->content_data['message']?></div>
											</div>
										</div>
										<?
										if($plugin_data['has_additional'] == "2"){
										$sql = "SELECT * FROM grouppluginoptions WHERE status IN ('2') AND plugin_id='".$plugin_data['plugin_id']."' AND group_id='".$this->member_data['group_id']."' ORDER BY created_date ASC";
										$stmt = $this->pdo->prepare($sql); $stmt->execute();
										while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
											if(empty($row['grid_location'])) { $row['grid_location'] = 'data-gs-auto-position="true" data-gs-width="4"'; }
											$grouppluginoptions[] = $row;
										}
										if(!empty($grouppluginoptions)) { ?>
											<div>
												<div class="grid-stack grid-stack-form-static">
													<? foreach($grouppluginoptions as $row) { ?>
														<div class="grid-stack-item" <?=$row['grid_location']?> data-gs-height="1">
															<div class="grid-stack-item-content">
																<? if($row['option_type'] == "input") { ?>
																	<label><?=$row['title']?></label>
																	<div class="grid-input-display"><?=$stmt_data->$row['title']?></div>
																<? } elseif($row['option_type'] == "checkbox") { ?>
																	<label><?=$row['title']?></label>
																	<div class="grid-input-display">
																		<? if($stmt_data->$row['title'] == "2") { ?>
																			<i class="fa fa-check text-success"></i>
																		<? } ?>
																	</div>
																<? } elseif($row['option_type'] == "select") { ?>
																	<label><?=$row['title']?></label>
																	<div class="grid-input-display"><?=$stmt_data->$row['title']?></div>
																<? } elseif($row['option_type'] == "label") { ?>
																	<div class="grid-option-label"><i class="fa fa-angle-double-right text-themed"></i> <?=$row['title']?></div>
																<? } ?>
															</div>
														</div>
													<? } ?>
												</div>
												<div class="clearfix"></div>
											</div>
										<? } } ?>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<? $i=0; if(!empty($cont_page_widg)) { foreach($cont_page_widg as $row){ $i++; ?>
								<div role="tabpanel" class="tab-pane fade" id="<?=$row['pl_title']?>_cp<?=$this->content_data['content_id']?>"><?
									$wigclass = new $row['pl_title']($this);
									$wigclass->page_widget($row,"-".$plugin_data['plugin_id'].",".$this->content_data['content_id']."-");
								?><div class="clearfix"></div></div>
							<? } } ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			$('.grid-stack-form-static').gridstack({
				disableDrag: true,
				disableResize: true,
				cellHeight: '30px',
				verticalMargin: '1px'
			});
			if(typeof resizeGrid == 'function') { resizeGrid(); }

			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				google.maps.event.trigger(map, "resize");
				map.setCenter(latlng);
			});

			var geocoder;
			var map;
			var address = "<?=$this->content_data['address']." ".$this->content_data['city']." ".$this->content_data['state']." ".$this->content_data['zip']?>";
			geocoder = new google.maps.Geocoder();
			var latlng = new google.maps.LatLng(-34.397, 150.644);
			var myOptions = {
				zoom: 11,
				center: latlng,
				mapTypeControl: true,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
				navigationControl: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				styles: [{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#71ABC3"},{"saturation":-10},{"lightness":-21},{"visibility":"simplified"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"hue":"#C3E0B0"},{"saturation":15},{"lightness":-12},{"visibility":"simplified"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#C3E0B0"},{"saturation":23},{"lightness":-12},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#A19FA0"},{"saturation":-98},{"lightness":-20},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#FFFFFF"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]}]
			};
			map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);

			if (geocoder) {
				geocoder.geocode({
					'address': address
				}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
							latlng = results[0].geometry.location;
							map.setCenter(results[0].geometry.location);

							var infowindow = new google.maps.InfoWindow({
								content: '<b>' + address + '</b>',
								size: new google.maps.Size(150, 50)
							});

							var marker = new google.maps.Marker({
								position: results[0].geometry.location,
								map: map,
								title: address
							});
							google.maps.event.addListener(marker, 'click', function() {
								infowindow.open(map, marker);
							});

						} else {
							alert("No results found");
						}
					} else {
						$("#map_error_output").html("<div class='alert alert-info small m-b-sm'>Unable to find address on google maps.</div>");
					}
				});
			}
		</script>
		<?
	}
}
?>
