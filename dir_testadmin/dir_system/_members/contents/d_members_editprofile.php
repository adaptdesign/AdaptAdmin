<?php

class d_members_editprofile extends global_members {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //===========================================================================================
	public function p_edit_profile() { if($_POST) { ob_start(); }
    if(!empty($this->content_data_id)) { $this->set_content_data("",$this->content_data_id); }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->content_data)) { $this->fail_page[] = "<div>Error: Unable to find member data"; }
    if($this->member_data['member_id'] != $this->content_data['member_id']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { if($_POST){ output_error($this->fail_page,1); }else{ output_error($this->fail_page); } return; } //==============
		?>
		<form class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_members")?>&action=<?=encode("p_edit_profile")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title" id="myModalLabel">
					<i class="fa fa-user f-c-themed"></i> Account Settings
				</h2>
			</div>

      <div class="p-l-20 p-r-20 b-s-0 b-dashed b-s-l-1 b-c-default relative">
        <div class="row m-0">
          <div class="col-md-3 p-l-0">
            <div class="p-10 bg-white">
              <h2 class="m-b-15"><i class="fa fa-hashtag f-c-themed"></i> <?=$this->content_data['member_hash']?></h2>
              <div class="cropmewrapper square">
                <!-- <form class="form_redirect form-inline cropmesubmit" enctype="multipart/form-data" link="<?=URL_PATH?>pipe.php?page=<?=encode("a_members")?>&action=<?=encode("p_profileimgupload")?>"> -->
                  <div class="cropme" style="" outwidth="500" outheight="500" thisinput="my_hidden">
                    <? if (empty($this->content_data['profile_img'])) { ?>
                      <img data-name="<?=$this->content_data['firstname']." ".$this->content_data['lastname']?>" class="myprofilepic profile_img" title="<?=$this->content_data['firstname']." ".$this->content_data['lastname']?>" style="width: 100%;border: 1px solid #999;">
                    <? } else { ?>
                      <img class="myprofilepic" src="<?=URL_PATH.$this->content_data['profile_img']?>" title="<?=$this->content_data['firstname']." ".$this->content_data['lastname']?>" style="width: 100%;border: 1px solid #999;">
                    <? } ?>
                  </div>
                  <input type="hidden" id="my_hidden" name="my_hidden" value="">
                  <input type="hidden" name="MAX_FILE_SIZE" value="99999999" />
                <!-- </form> -->
              </div>
            </div>
          </div>
          <div class="col-md-9 p-0">

            <ul class="nav nav-tabs nav-hash bg-white">
              <li class="active"><a data-toggle="tab" href="#tb1">Profile</a></li>
              <li class=""><a data-toggle="tab" href="#tb2">Update Password</a></li>
            </ul>
            <div class="tab-content bg-white p-15">
              <div class="tab-pane animated fadeIn fade active in" id="tb1">
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-2 p-t-10 f-s-13 uppercase">First Name:</label>
    							<div class="col-md-10"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("firstname")?>]" value="<?=$this->content_data['firstname']?>"></div>
    						</div>
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-2 p-t-10 f-s-13 uppercase">Last Name:</label>
    							<div class="col-md-10"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("lastname")?>]" value="<?=$this->content_data['lastname']?>"></div>
    						</div>
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-2 p-t-10 f-s-13 uppercase">E-mail:</label>
    							<div class="col-md-10"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("email")?>]" value="<?=$this->content_data['email']?>"></div>
    						</div>
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-2 p-t-10 f-s-13 uppercase">About Me:</label>
    							<div class="col-md-10"><div class="b-s-1 b-solid b-c-default"><textarea class="form-control tinymce" name="<?=encode("inputs")?>[<?=encode("profile")?>]"><?=$this->content_data['profile']?></textarea></div></div>
    						</div>
              </div>
              <div class="tab-pane animated fadeIn fade" id="tb2">
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-2 p-t-10 f-s-13 uppercase">Current Password:</label>
    							<div class="col-md-10"><input class="form-control" type="password" name="<?=encode("inputs")?>[<?=encode("currentpassword")?>]" autocomplete="off"></div>
    						</div>
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-2 p-t-10 f-s-13 uppercase">New Password:</label>
    							<div class="col-md-10"><input class="form-control" type="password" name="<?=encode("inputs")?>[<?=encode("password")?>]" autocomplete="off"></div>
    						</div>
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-2 p-t-10 f-s-13 uppercase">Re-enter New Password:</label>
    							<div class="col-md-10"><input class="form-control" type="password" name="<?=encode("inputs")?>[<?=encode("confirmpassword")?>]" autocomplete="off"></div>
    						</div>
              </div>
            </div>

          </div>
        </div>
      </div>
			<div class="modal-footer">
				<input type="hidden" name="<?=encode("wheres")?>[<?=encode("member_id")?>]" value="<?=encode($this->content_data['member_id'])?>">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-themed">Submit</button>
			</div>
		</form>
		<?
    if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>ob_get_clean(),'js'=>'')); }
	}
  //=========================================



}
?>
