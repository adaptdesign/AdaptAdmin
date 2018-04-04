<?php

class d_groups_item extends global_groups {

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
            <? if(empty($this->content_data['group_logo'])) { ?>
              <img class="w-100p" src="<?=URL_PATH?>dir_img/group_logo.png">
            <? } else { ?>
              <img class="w-100p" src="<?=URL_PATH.$this->content_data['group_logo']?>">
            <? } ?>
          </div>
        </div>
        <div class="col-md-9 p-0">
          <div class="p-15 bg-white">
            <? if($this->content_data['member_id'] != $this->member_data['member_id']) { ?>
              <?
              $stmt = $this->pdo->prepare("SELECT * FROM connections WHERE connection_type='groupmember' AND group_id=:group_id AND member_id=:member_id");
    					$stmt->execute(array(':group_id' => $this->content_data['group_id'], ':member_id' => $this->member_data['member_id']));
    					if($stmt->rowCount() == 1) {
                $groupmember_data = $stmt->fetch(PDO::FETCH_ASSOC);
                if($groupmember_data['approval'] == "2") { ?>
                  <a class="do_ajax btn btn-default pull-right m-l-xs" da_link="<?=URL_PATH?>?page=<?=encode("a_groupmembers")?>&action=<?=encode("p_delete_groupmember")?>" da_type="reload" da_target="location" da_verify="1" message="Are you sure you want leave this group?" data-wheres[connection_id]="<?=encode($groupmember_data['connection_id'])?>">Leave Group</a>
                <? } else { ?>
                  <a class="do_ajax btn btn-danger pull-right m-l-xs" da_link="<?=URL_PATH?>?page=<?=encode("a_groupmembers")?>&action=<?=encode("p_delete_groupmember")?>" da_type="reload" da_target="location" da_verify="1" da_message="Are you sure you want to remove your request to join <?=$this->content_data['title']?>?" data-wheres[connection_id]="<?=encode($groupmember_data['connection_id'])?>">Pending Approval</a>
                 <? }
              } else { ?>
    						<a class="do_ajax btn btn-themed pull-right m-l-xs" da_type="reload" da_target="location" da_verify="1" data-inputse[group_id]="<?=encode($this->content_data['group_id'])?>" da_link="<?=URL_PATH?>?page=<?=encode("a_groupmembers")?>&action=<?=encode("p_add_groupmember")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>" da_message="Request to join <?=$this->content_data['title']?>?"><i class="fa fa-user-plus"></i> Join Group</a>
              <? } ?>
            <? } ?>

            <h2 class="p-t-10"><i class="fa fa-hashtag f-c-themed"></i> <?=$this->content_data['group_hash']?></h2>
            <div class="clearfix m-b-15"></div>
            <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
              <label class="col-md-2 p-t-10 f-s-13 uppercase">Description:</label>
              <div class="col-md-10"><div class="b-s-1 b-solid b-c-default bg-white h-n-50 p-10"><?=e_a($this->content_data,'description')?></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
		<?
	}
}
?>
