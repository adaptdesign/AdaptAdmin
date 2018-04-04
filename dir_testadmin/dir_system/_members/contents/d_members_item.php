<?php

class d_members_item extends global_members {

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
    <div class="b-s-0 b-dashed b-s-l-1 b-c-default relative">
      <div class="row m-0">
        <div class="col-md-3 p-l-0">
          <div class="p-10 bg-white">
            <? if (empty($this->content_data['profile_img'])) { ?>
              <img class="w-100p profile_img" data-name="<?=$this->content_data['firstname']." ".$this->content_data['lastname']?>">
            <? } else { ?>
              <img class="w-100p" src="<?=URL_PATH.$this->content_data['profile_img']?>">
            <? } ?>
          </div>
        </div>
        <div class="col-md-9 p-0">
          <div class="p-15 bg-white">
            <? if($this->member_data['member_id'] == $this->content_data['member_id']) { ?>
              <a class="do_ajax btn btn-themed pull-right m-l-xs" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_members_editprofile")?>&action=<?=encode("p_edit_profile")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($this->content_data['member_id'])?>"><i class="fa fa-plus"></i> Edit Account</a>
            <? } ?>
            <h2 class="p-t-10"><i class="fa fa-hashtag f-c-themed"></i> <?=$this->content_data['member_hash']?></h2>
            <div class="clearfix m-b-15"></div>
            <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
              <label class="col-md-2 p-t-10 f-s-13 uppercase">About Me:</label>
              <div class="col-md-10"><div class="b-s-1 b-solid b-c-default bg-white h-n-50 p-10"><?=$this->content_data['profile']?></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?
  }



}
?>
