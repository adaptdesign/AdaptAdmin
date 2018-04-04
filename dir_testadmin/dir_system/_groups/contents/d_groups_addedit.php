<?php

class d_groups_addedit extends global_groups {

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
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { if($_POST){ output_error($this->fail_page,1); }else{ output_error($this->fail_page); } return; } //==============
		?>
    <form class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_groups")?>&action=<?=encode("p_add_group")?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title" id="myModalLabel">
					<i class="fa fa-handshake-o f-c-themed"></i> Groups: Add New
				</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-2 p-t-10 f-s-13 uppercase">Name:</label>
							<div class="col-md-10"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("title")?>]" value="<?=e_a($this->content_data,'title')?>"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-sm btn-themed">Submit</button>
			</div>
		</form>
		<?
    if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>ob_get_clean(),'js'=>'')); }
	}
}
?>
