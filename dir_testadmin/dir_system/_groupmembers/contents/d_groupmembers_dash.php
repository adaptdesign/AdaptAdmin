<?php

class d_groupmembers_dash extends global_groupmembers {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
	//=========================================
  public function p_feed_query() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // GET QUERY
    $return = $this->set_query(); $sql = $return[0]; $arg = $return[1];
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    $return = ""; //$return = disarray($sql,$arg);
    $stmt = $this->pdo->prepare($sql); $results = array();
    if($stmt->execute($arg)) {
      while($row = $this->_format_row_dbtype($stmt->fetch(PDO::FETCH_ASSOC))){
        $row['r_id'] = $row['connection_id'];
        $row['html'] = $this->show_feed_item($row);
        $results[] = $row;
      }
    }
    echo json_encode(array('status'=>'success','message'=>$return,'data'=>json_encode($results),'js'=>$this->feed_js()));
	}
	//===========================================================================================
	public function p_show_feed() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page); return; } //==============
		?>
    <div class="clearfix">

      <div class="hs-block">
        <ul class="nav nav-pills nav-stacked m-t-15">
          <li class=" carrotsmall">
            <a class="collapsed p-l-50 f-c-themed f-c-h-dark b-s-0 b-solid b-c-l-themed" data-toggle="collapse" href="#fs_gm"><h3><i class="fa fa-handshake-o f-c-themed"></i> groupmembers<span></span></h3></a>
            <ul id="fs_gm" class="nav nav-pills nav-stacked collapse b-s-0 b-s-t-1 b-dashed b-c-default">
              <li>
                <a class="cnfm_me2 p-l-50 f-c-default f-c-h-themed b-s-0 b-solid b-c-l-themed" link="<?=URL_PATH?>pipe.php?page=<?=encode("a_members")?>&action=<?=encode("p_edit_member")?>" type="3" isform="" message="" data-inputse[group_id]="<?=encode($row['group_id'])?>" data-wheres[member_id]="<?=encode($this->member_data['member_id'])?>"><h5><i class="fa fa-users f-c-themed"></i> Group Members</h5></a>
              </li>
            </ul>
          </li>
        </ul>
        <div class="f-s-18 m-t-2 f-w-500" style="display:none;"><i class="fa fa-users f-c-themed"></i> groupmembers</div>
      </div>
      <div class="feedlooper m-t-15" id="feedlooper_mygroupmembers" page="1"  link="<?=URL_PATH?>?page=<?=encode("d_groupmembers_dash")?>&action=<?=encode('p_feed_query')?>&link=<?=encode("-".$this->plugins['members']['plugin_id'].",".$this->member_data['member_id']."-")?>">

      </div>
      <div id="feedlooper_mygroupmembers_msgbox"></div>

    </div>
    <? echo $this->feed_js(); ?>
		<?
	}
  //=========================================
  public function feed_js() { ob_start();
		?>
		<script>
			$(".profile_img").initial();
			$('[title]').tooltip({ container:'body', placement:'bottom' });
		</script>
		<?
    return ob_get_clean();
	}
	//===========================================================================================
	public function show_feed_item($row) { ob_start();
		?>
    <div r_id="<?=e_a($row,'r_id')?>" class="bg-white fx_transition2 fx_box_shadow1_h p-2 m-b-10">
      <div class="clearfix elementlink div-table" href="<?=URL_PATH?>members/<?=$row['member_id']?>" title="View <?=$row['firstname']." ".$row['lastname']?>'s profile">
        <div class="">
          <? if(empty($row['profile_img'])) { ?>
            <img class="w-35 b-r-5 profile_img" data-name="<?=$row['firstname']." ".$row['lastname']?>">
          <? } else { ?>
            <img class="w-35 b-r-5" src="<?=URL_PATH.$row['profile_img']?>">
          <? } ?>
        </div>
        <div class="hs-table-cell">
          <div class="p-t-10 m-l-5 f-s-11 f-w-500 uppercase"><?=$row['firstname']." ".$row['lastname']?></div>
        </div>
        <div class="w-100p text-right">
          <div class="m-t-10 m-l-2 f-s-10 f-c-gray">
            <? if($this->_check_online($row['last_active'])) { ?><i class='fa fa-circle f-c-success'></i>
          <? } else { ?><?=$this->_time_diff($row['last_active'],1)?><? } ?>
          </div>
        </div>
      </div>

      <? if($row['approval'] == "1") { ?>
        <div class="clearfix m-t-2 p-t-2 b-s-0 b-c-default b-dashed b-s-t-1 hs-block">
          <a class="do_ajax pull-right btn btn-xs btn-danger m-l-3" da_type="reload" da_target="location" da_verify="1" data-wheres[connection_id]="<?=encode($row['connection_id'])?>" da_link="<?=URL_PATH?>?page=<?=encode("a_groupmembers")?>&action=<?=encode("p_delete_groupmember")?>" da_message="Are you sure you want to deny <?=$row['firstname']." ".$row['lastname']?>'s request to join the group?"><i class="fa fa-times"></i></a>
          <a class="do_ajax pull-right btn btn-xs btn-success" da_type="reload" da_target="location" da_verify="1" data-inputse[approval]="<?=encode("2")?>" data-wheres[connection_id]="<?=encode($row['connection_id'])?>" da_link="<?=URL_PATH?>?page=<?=encode("a_groupmembers")?>&action=<?=encode("p_edit_groupmember")?>" da_message="Accept <?=$row['firstname']." ".$row['lastname']?>'s request to join the group?"><i class="fa fa-check"></i></a>
          <div class="f-s-11 f-w-500 m-t-3">Pending Approval</div>
        </div>
      <? } ?>
    </div>
		<?
    return ob_get_clean();
	}


}
?>
