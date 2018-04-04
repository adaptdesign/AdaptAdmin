<?php

class d_layout_addeditwidget extends global_layout {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_edit() {
    if(!empty($this->content_data_id)) { $this->set_content_data("",$this->content_data_id); }
    if(empty($this->content_data)) { $this->fail_page[] = "Unable to load content data"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { if($_POST){ output_error($this->fail_page,1); }else{ output_error($this->fail_page); } return; } //==============
    $this->p_addedit();
  }
	//=========================================
  public function p_addedit() { if($_POST) { ob_start(); }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { if($_POST){ output_error($this->fail_page,1); }else{ output_error($this->fail_page); } return; } //==============
    // SET ADD OR EDIT
    if(!empty($this->content_data)) { $modal_type = "edit"; }
    else { $modal_type = "add"; }

    $parent_id = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', decode($_GET['parent']));
    $type = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', decode($_GET['type']));

    $current_widgets = array();
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE group_id=:group_id AND parent_id=:parent_id AND layout_type=:layout_type ");
    $stmt->execute(array(':group_id' => $this->member_data['group_id'], ':parent_id' => $parent_id, ':layout_type' => $type));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      array_push($current_widgets, $row['plugin_id']);
    }
		?>
    <form class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_".$modal_type."widget")?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title" id="myModalLabel">
					<i class="fa fa-cube f-c-themed"></i> Widgets: <? if($modal_type == "edit"){ echo "Edit"; } else { echo "Add"; } ?>
				</h2>
			</div>
			<div class="modal-body">
				<div class="row m-b-xs">
					<div class="col-md-12">
            <? if($modal_type == "add") { ?>
  						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  							<label class="col-md-2 p-t-10 f-s-13 uppercase">Plugin:</label>
  							<div class="col-md-10">
                  <select class="form-control select2" name="<?=encode("inputse")?>[<?=encode("plugin_id")?>]">
  									<option value="">Select a Plugin</option>
  									<?
  									if($type == "widg_page_page" || $type == "widg_cont_page") {
  										if(!empty($this->plugins)) { foreach($this->plugins as $plugin) { if($plugin['has_pagewidget'] == "2" && $plugin['plugin_id'] != $parent_id && !in_array($plugin['plugin_id'], $current_widgets)) {
  											?><option value="<?=encode($plugin['plugin_id'])?>"><?=$plugin['title']?></option><?
  										} } }
  									}
  									?>
  								</select>
                </div>
  						</div>
            <? } ?>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase" title="Select whether posted content will be visible for the entire group or only to the member who posted it and those linked to it">Posting Privacy: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
							<div class="col-md-10">
                <select class="form-control select2" name="<?=encode("inputse")?>[<?=encode("privacy")?>]">
									<option value="<?=encode("1")?>" <? if($this->content_data['privacy'] == "1") { echo "selected"; } ?>>Entire Group</option>
									<option value="<?=encode("2")?>" <? if($this->content_data['privacy'] == "2") { echo "selected"; } ?>>Linked Members</option>
								</select>
              </div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase" title="Select whether you would like content posted anywhere in the group or only content posted to this widget/page to be displayed">Widget Content: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
							<div class="col-md-10">
                <select class="form-control select2" name="<?=encode("inputse")?>[<?=encode("widgetonly")?>]">
									<option value="<?=encode("1")?>" <? if($this->content_data['widgetonly'] == "1") { echo "selected"; } ?>>Posted Anywhere</option>
									<option value="<?=encode("2")?>" <? if($this->content_data['widgetonly'] == "2") { echo "selected"; } ?>>Posted To Widget</option>
								</select>
              </div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Alternate Title:</label>
							<div class="col-md-10"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("title")?>]" value="<?=$this->content_data['title']?>"></div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Alternate Icon: <a href="http://fontawesome.io/icons/" target="_target" class="text-muted small">[Icon List]</a></label>
							<div class="col-md-10"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("font_icon")?>]" value="<?=$this->content_data['font_icon']?>"></div>
						</div>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Access Level:</label>
							<div class="col-md-10">
                <select class="form-control select2" name="<?=encode("inputse")?>[<?=encode("level")?>]">
									<option value="<?=encode('2')?>" <? if($this->content_data['level'] == "2") { echo "selected"; } ?>>Everyone</option>
									<option value="<?=encode('3')?>" <? if($this->content_data['level'] == "3") { echo "selected"; } ?>>Admins</option>
									<option value="<?=encode('4')?>" <? if($this->content_data['level'] == "4") { echo "selected"; } ?>>Super Admins</option>
								</select>
              </div>
						</div>
            <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase" title="Select which plugins new content will be linkable to">Linkable To: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
							<div class="col-md-10">
                <select class="form-control select2" multiple="multiple" name="opt_linkables[]">
  								<? if(!empty($this->content_data['opt_linkables'])) { $opt_linkables_array = explode(",", $this->content_data['opt_linkables']); } ?>
  								<? foreach($this->plugins as $plugin) { if($plugin['status'] > "1" && $plugin['has_pagewidget'] == "2") { ?>
  									<option value="<?=$plugin['title']?>" <? if(!empty($plugin['title']) && !empty($opt_linkables_array)) { if(in_array($plugin['title'], $opt_linkables_array)) { echo "selected"; } } ?>><?=$plugin['title']?></option>
  								<? } } ?>
  							</select>
              </div>
						</div>

            <div class="clearfix bg-lightgray p-10 p-t-20 p-b-20 b-s-0 b-dashed b-c-default b-s-b-1">
              <label class="col-md-2" title="Select this option if you would like to require new content to be approved by and admin before posting">Require Approval? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
              <div class="col-md-10">
                <div class="input-group">
                  <div class="checkbox checkbox-success checkbox-inline">
                    <input type="hidden" name="<?=encode("inputse")?>[<?=encode("approval")?>]" value="<?=encode("1")?>">
                    <input name="<?=encode("inputse")?>[<?=encode("approval")?>]" value="<?=encode("2")?>" type="checkbox" <? if($this->content_data['approval'] == '2') { echo "checked"; }?>>
                    <label></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix bg-lightgray p-10 p-t-20 p-b-20 b-s-0 b-dashed b-c-default b-s-b-1">
              <label class="col-md-2" title="Check this option if you wish for group members to receive notifications when new content is added to this widget">Notifications? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
              <div class="col-md-10">
                <div class="input-group">
                  <div class="checkbox checkbox-success checkbox-inline">
                    <input type="hidden" name="<?=encode("inputse")?>[<?=encode("notifications")?>]" value="<?=encode("1")?>">
                    <input name="<?=encode("inputse")?>[<?=encode("notifications")?>]" value="<?=encode("2")?>" type="checkbox" <? if($this->content_data['notifications'] == '2') { echo "checked"; }?>>
                    <label></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix bg-lightgray p-10 p-t-20 p-b-20 b-s-0 b-dashed b-c-default b-s-b-1">
              <label class="col-md-2" title="Select this option to disable access to this widget.">Disable Widget? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
              <div class="col-md-10">
                <div class="input-group">
                  <div class="checkbox checkbox-success checkbox-inline">
                    <input type="hidden" name="<?=encode("inputse")?>[<?=encode("status")?>]" value="<?=encode("2")?>">
                    <input name="<?=encode("inputse")?>[<?=encode("status")?>]" value="<?=encode("1")?>" type="checkbox" <? if($this->content_data['status'] == '1') { echo "checked"; }?>>
                    <label></label>
                  </div>
                </div>
              </div>
            </div>


					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="widg" value="<?=encode($widg)?>">
				<input type="hidden" name="link" value="<?=encode($link)?>">
				<? if($modal_type == "edit") { ?>
					<input type="hidden" name="<?=encode("wheres")?>[<?=encode("layout_id")?>]" value="<?=encode($this->content_data['layout_id'])?>">

          <? if($this->member_data['group_level'] > '3' || $this->content_data['member_id'] == $this->member_data['member_id']) { ?>
						<a class="cnfm_me btn btn-danger pull-left" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_deletewidget")?>" istype="" isform="" message="Are you sure you want to delete this widget?" data-item_id="<?=encode($this->content_data['layout_id'])?>">Delete</a>
					<? } ?>

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-themed">Update</button>
				<? } else { ?>
					<input type="hidden" name="<?=encode("inputse")?>[<?=encode("layout_type")?>]" value="<?=encode($type)?>">
					<input type="hidden" name="<?=encode("inputse")?>[<?=encode("parent_id")?>]" value="<?=encode($parent_id)?>">

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-themed">Add</button>
				<? } ?>
			</div>
		</form>
    <?
    if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>ob_get_clean(),'js'=>'')); }
  }

}
?>
