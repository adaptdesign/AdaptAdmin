<?php

class d_groupsettings_feed extends global_groupsettings {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
	//=========================================
  public function p_show_feed() {
    $this->set_content_data();
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
    if(empty($this->content_data)) { $this->fail_page[] = "Unable to load content data"; }
		if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page); return; } //==============
		?>
		<div class="b-s-0 b-dashed b-s-l-1 b-c-default relative">

      <div class="row equal m-0 m-t-15">
        <div class="col-md-3 p-l-0">

          <ul class="nav nav-tabs nav-stacked nav-hash bg-white" hash_id="5ac00c7e4a933">
            <li class="active"><a data-toggle="tab" href="#gs1">Settings</a></li>
            <li><a data-toggle="tab" href="#gs2">Group Statistics</a></li>
            <li><a data-toggle="tab" href="#gs3">Payment Information</a></li>
          </ul>
        </div>
        <div class="col-md-9 p-0">
          <div class="tab-content bg-white p-15">
            <div class="tab-pane animated fadeIn fade active in" id="gs1">
              <form id="groupupdate" class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>pipe.php?page=<?=encode("a_groupsettings")?>&action=<?=encode("p_edit_group")?>">

  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3 p-t-10 f-s-13 uppercase">Group Name:</label>
  								<div class="col-md-9"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("title")?>]" value="<?=e_a($this->content_data,'title')?>"></div>
  							</div>
  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3 p-t-10 f-s-13 uppercase" title="Group Hashtag is used to help users search for the group. It is also used to verify plugins with website features. Group Hashtag cannot be changed.">Group Hashtag: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
  								<div class="col-md-9"><input class="form-control" type="text" disabled value="<?=e_a($this->content_data,'group_hash')?>"></div>
  							</div>
  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3 p-t-10 f-s-13 uppercase">Group Description:</label>
  								<div class="col-md-9"><div class="b-s-1 b-solid b-c-default"><textarea class="form-control tinymce" name="<?=encode("inputs")?>[<?=encode("description")?>]"><?=e_a($this->content_data,'description')?></textarea></div></div>
  							</div>
  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3 p-t-10 f-s-13 uppercase">Group Logo:</label>
  								<div class="col-md-3">
  									<div class="cropmewrapper square b-s-1 b-solid b-c-default bg-white">
  										<div class="cropme" item_id="2" outwidth="200" outheight="200" thisinput="my_hidden">
  											<? if(!empty(e_a($this->content_data,'group_logo'))) { ?><img src="<?=URL_PATH.$this->content_data['group_logo']?>"><? } ?>
  										</div>
  										<input type="hidden" id="my_hidden" name="my_hidden" value="">
  										<input type="hidden" name="MAX_FILE_SIZE" value="99999999" />
  									</div>
  								</div>
  							</div>
  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3" title="Uncheck if you want to keep the group hidden from non members.">Visible in Search <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
  								<div class="col-md-9">
  									<div class="input-group">
  										<div class="checkbox checkbox-success checkbox-inline">
  											<input type="hidden" name="<?=encode("inputse")?>[<?=encode("visible")?>]" value="<?=encode("1")?>">
  											<input name="<?=encode("inputse")?>[<?=encode("visible")?>]" value="<?=encode("2")?>" type="checkbox" <? if(e_a($this->content_data,'visible') == '2') { echo "checked"; }?>>
  											<label></label>
  										</div>
  									</div>
  								</div>
  							</div>
  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3" title="Uncheck if you want to prevent users from joining or requesting to join the group.">Accepting Members? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
  								<div class="col-md-9">
  									<div class="input-group">
  										<div class="checkbox checkbox-success checkbox-inline">
  											<input type="hidden" name="<?=encode("inputse")?>[<?=encode("join_allow")?>]" value="<?=encode("1")?>">
  											<input id="<?$c=uniqid()?><?=$c?>" name="<?=encode("inputse")?>[<?=encode("join_allow")?>]" value="<?=encode("2")?>" type="checkbox" <? if(e_a($this->content_data,'join_allow') == '2') { echo "checked"; }?>>
  											<label for="<?=$c?>"></label>
  										</div>
  									</div>
  								</div>
  							</div>
  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3" title="Check this option if you wish to require admin approval for users requesting to join the group.">Require Approval? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
  								<div class="col-md-9">
  									<div class="input-group">
  										<div class="checkbox checkbox-success checkbox-inline">
  											<input type="hidden" name="<?=encode("inputse")?>[<?=encode("join_approval")?>]" value="<?=encode("1")?>">
  											<input id="<?$c=uniqid()?><?=$c?>" name="<?=encode("inputse")?>[<?=encode("join_approval")?>]" value="<?=encode("2")?>" type="checkbox" <? if(e_a($this->content_data,'join_approval') == '2') { echo "checked"; }?>>
  											<label for="<?=$c?>"></label>
  										</div>
  									</div>
  								</div>
  							</div>
  							<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
  								<label class="col-md-3" title="Disable the group?">Disabled? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
  								<div class="col-md-9">
  									<div class="input-group">
  										<div class="checkbox checkbox-danger checkbox-inline">
  											<input type="hidden" name="<?=encode("inputse")?>[<?=encode("status")?>]" value="<?=encode("2")?>">
  											<input id="<?$c=uniqid()?><?=$c?>" name="<?=encode("inputse")?>[<?=encode("status")?>]" value="<?=encode("1")?>" type="checkbox" <? if(e_a($this->content_data,'status') == '1') { echo "checked"; }?>>
  											<label for="<?=$c?>"></label>
  										</div>
  									</div>
  								</div>
  							</div>
  							<div class="clearfix m-t-15">
  								<? if($this->member_data['group_status'] == "2") { if($this->member_data['member_level'] > '2' || $this->content_data['member_id'] == $this->member_data['member_id']) { ?>
  									<a class="cnfm_me btn btn-danger pull-left" link="<?=URL_PATH?>pipe.php?page=<?=encode("groups")?>&action=<?=encode("a_delete_group")?>" istype="2" isform="" message="Are you sure you want to permanently delete this group?" data-item_id="<?=encode($this->content_data['group_id'])?>"><i class="fa fa-times"></i> Delete Group</a>
  								<? } } ?>
  								<input type="hidden" name="<?=encode("wheres")?>[<?=encode("group_id")?>]" value="<?=encode($this->content_data['group_id'])?>">
  								<button type="submit" class="btn btn-themed pull-right"><i class="fa fa-check"></i> Update</button>
  							</div>
  						</form>
            </div>
          </div>
        </div>
      </div>

		</div>
		<?
	}

}
?>
