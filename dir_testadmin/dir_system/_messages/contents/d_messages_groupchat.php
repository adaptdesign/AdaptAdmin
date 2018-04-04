<?php

class d_messages_groupchat extends global_messages {

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
    $return = $this->set_query("","","group"); $sql = $return[0]; $arg = $return[1];
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    $return = ""; //$return = disarray($sql,$arg);
    $stmt = $this->pdo->prepare($sql); $results = array();
    if($stmt->execute($arg)) {
      while($row = $this->_format_row_dbtype($stmt->fetch(PDO::FETCH_ASSOC))){
        $row['r_id'] = $row['message_id'];
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
    <div class="chat_window_box">
      <div class="chat_window_title">
        <a class="pull-right f-c-lightgray f-c-h-themed"><i class="fa fa-times"></i></a>
        <? if($this->_check_online(e_a($this->content_data,'last_active'))) { ?><i class='fa fa-circle f-s-10 f-c-success'></i>
        <? } else { ?><?=$this->_time_diff(e_a($this->content_data,'last_active'),1)?><? } ?> <?=e_a($this->content_data,'firstname')?> <?=e_a($this->content_data,'lastname')?>
      </div>
      <div class="chat_window_content">
        <div class="feedlooper" id="feedlooper_mymessager" page="1" link="<?=URL_PATH?>?page=<?=encode("d_messages_chat")?>&action=<?=encode('p_feed_query')?>&type=<?=encode('private')?>">
        </div>
        <div id="feedlooper_mymessager_msgbox"></div>
      </div>
      <div class="chat_window_input">dd</div>
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
    <div r_id="<?=$row['r_id']?>" class="bg-white fx_transition2 fx_box_shadow1_h p-2 m-b-10">
      <div class="clearfix div-table">
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

      <div class="clearfix m-t-2 p-t-2 b-s-0 b-c-default b-dashed b-s-t-1 hs-block">
        dd
      </div>
    </div>
		<?
    return ob_get_clean();
	}


}
?>
