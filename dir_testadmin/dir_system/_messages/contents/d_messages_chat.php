<?php

class d_messages_chat extends global_messages {

  //=========================================
  public function __construct(){
		parent::__construct();

  }

	//=========================================
  public function p_feed_query() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty(e_a($_SESSION,'chat_id',1))) { $this->fail_page[] = "Chat ID is not set"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // GET QUERY
    $return = $this->set_query("","","private"); $sql = $return[0]; $arg = $return[1];
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
	public function p_show_feed() { if($_POST) { ob_start(); }
    if(!empty($_POST['chat_id'])) { if($_POST['chat_id'] == "none"){ unset($_SESSION['chat_id']); } else { $_SESSION['chat_id'] = clean($_POST['chat_id']); } }

    $stmt = $this->pdo->prepare("SELECT member_id, status, approval, member_level, firstname, lastname, last_active FROM members WHERE member_id=:member_id LIMIT 1");
    $stmt->execute(array(':member_id' => e_a($_SESSION,'chat_id',1)));
    if($stmt->rowCount() == 1) { $this->content_data = $stmt->fetch(PDO::FETCH_ASSOC); }
		// CHECK FOR ERRORS
		if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->content_data)) { $this->fail_page[] = "Missing member ID"; }
		// DISPLAY ERRORS
    if(!empty($this->fail_page)) {
      if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>'','js'=>'')); return; }
      else { return; }
    }
		?>
    <div class="chat_window_box <? if(e_a($_SESSION,'chat_minimized') == "yes") { echo "chat_hide"; } ?>">
      <div class="chat_window_title">
        <a class="pull-right f-c-lightgray f-c-h-themed m-l-15 do_ajax" da_target="chat_window" da_type="load" da_link="<?=URL_PATH?>?page=<?=encode("d_messages_chat")?>&action=<?=encode('p_show_feed')?>" data-chat_id="none"><i class="fa fa-times"></i></a>
        <a class="pull-right f-c-lightgray f-c-h-themed" id="chat_hide"><i class="fa fa-minus"></i></a>
        <i class="fa fa-comments f-s-14 m-r-3 f-c-themed"></i>
        <? if($this->_check_online(e_a($this->content_data,'last_active'))) { ?><i class='fa fa-circle f-s-10 m-r-3 f-c-success'></i> <? } ?>
        <?=e_a($this->content_data,'firstname')?> <?=e_a($this->content_data,'lastname')?>
      </div>
      <div class="chat_window_content">
        <div id="feedlooper_mymessager_msgbox"></div>
        <div class="feedlooper" id="feedlooper_mymessager" page="1" link="<?=URL_PATH?>?page=<?=encode("d_messages_chat")?>&action=<?=encode('p_feed_query')?>&type=<?=encode('private')?>">
        </div>
      </div>
      <div class="chat_window_input"><input type="text" class="form-control f-s-12" style="height: 30px; padding: 5px; border: none;" placeholder="Type message..."></div>
    </div>
		<? echo $this->feed_js(); ?>
		<?
    if($_POST) { echo json_encode(array('status'=>'success','message'=>'','data'=>ob_get_clean(),'js'=>'')); }
	}
  //=========================================
  public function feed_js() { ob_start();
		?>
		<script>
      $(".chat_window_content").stop().animate({ scrollTop: $(".chat_window_content")[0].scrollHeight}, 1000);
			$(".profile_img").initial();
			$('[title]').tooltip({ container:'body', placement:'bottom' });
		</script>
		<?
    return ob_get_clean();
	}
	//===========================================================================================
	public function show_feed_item($row) { ob_start();
    if($row['member_id'] != $this->member_data['member_id']) {
      $stmt = $this->pdo->prepare("SELECT member_id, firstname, lastname, member_hash, profile_img FROM members WHERE member_id=:member_id LIMIT 1");
      $stmt->execute(array(':member_id' => $row['member_id']));
      if($stmt->rowCount() == 1) { $usr_data = $stmt->fetch(PDO::FETCH_ASSOC); $pull="pull-left"; $margin="m-r-5"; }
    } else {
      $usr_data['member_id'] = $this->member_data['member_id'];
      $usr_data['firstname'] = $this->member_data['firstname'];
      $usr_data['lastname'] = $this->member_data['lastname'];
      $usr_data['profile_img'] = $this->member_data['profile_img'];
      $pull="pull-right"; $margin="m-l-5";
    }
		?>
    <div r_id="<?=$row['r_id']?>" class="clearfix p-2 m-t-15">
      <? if(empty($usr_data['profile_img'])) { ?>
        <img class="w-35 b-r-2 profile_img <?=$pull?> <?=$margin?>" data-name="<?=$usr_data['firstname']." ".$usr_data['lastname']?>">
      <? } else { ?>
        <img class="w-35 b-r-2 <?=$pull?> <?=$margin?>" src="<?=URL_PATH.$usr_data['profile_img']?>">
      <? } ?>
      <div class="bg-white f-s-12 p-5 fx_box_shadow1 <?=$pull?>">
        <?=$row['message']?>
      </div>
    </div>
		<?
    return ob_get_clean();
	}


}
?>
