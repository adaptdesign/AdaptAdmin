<?php

class d_layout_addeditfolder extends global_layout {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
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
		?>
		<form class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_".$modal_type."folder")?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title" id="myModalLabel">
					<? if($modal_type == "edit"){ ?><i class="fa fa-folder f-c-themed"></i> Folders: Edit<? } else { ?><i class="fa fa-folder f-c-themed"></i> Folders: Add New<? } ?>
				</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-4 p-t-10 f-s-13 uppercase">Name:</label>
							<div class="col-md-8"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("title")?>]" value="<?=$this->content_data['title']?>"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<? if($modal_type == "edit") { ?>
					<input type="hidden" name="<?=encode("wheres")?>[<?=encode("layout_id")?>]" value="<?=encode($this->content_data['layout_id'])?>">
					<? if($this->member_data['group_level'] > '3' || $this->content_data['member_id'] == $this->member_data['member_id']) { ?>
						<a class="cnfm_me btn btn-sm btn-danger pull-left" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("a_deletefolder")?>" istype="" isform="" message="Are you sure you want to delete this folder?" data-item_id="<?=encode($this->content_data['layout_id'])?>">Delete</a>
					<? } ?>
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-sm btn-themed">Update</button>
				<? } else { ?>
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-sm btn-themed">Submit</button>
				<? } ?>
			</div>
		</form>
		<?
    if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>ob_get_clean(),'js'=>'')); }
	}


}
?>
