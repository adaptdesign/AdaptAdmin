<?php

class d_messages_conversations extends global_messages {

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
    $return = $this->set_query("","","conversations"); $sql = $return[0]; $arg = $return[1];
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
    <div class="feedlooper" id="feedlooper_mymessager" page="1" link="<?=URL_PATH?>?page=<?=encode("d_messages_conversations")?>&action=<?=encode('p_feed_query')?>">
    </div>
    <div id="feedlooper_mymessager_msgbox"></div>
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
    if($row['member_id'] == $this->member_data['member_id']){ $other_id = $row['to_member_id']; } else { $other_id = $row['member_id']; }
		?>
    <div r_id="<?=$row['r_id']?>" class="bg-white bg-l-h-primary c-pointer fx_transition2 fx_box_shadow1_h p-2 m-b-10 do_ajax" da_target="chat_window" da_type="load" da_link="<?=URL_PATH?>?page=<?=encode("d_messages_chat")?>&action=<?=encode('p_show_feed')?>" data-chat_id="<?=$other_id?>">
      <div class="clearfix f-s-11 p-2">
        <?=(strlen($row['message']) > 100) ? substr($row['message'],0,97).'...' : $row['message']?>
      </div>
      <div class="clearfix m-t-2 p-t-2 b-s-0 b-c-default b-dashed b-s-t-1 hs-block">
        <ul class="clearfix ul-unstyled ul-inline relative">
          <li class="pull-right">
            <a class="do_ajax f-c-lightgray f-c-h-themed" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_tasks_addedit")?>&action=<?=encode("p_edit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($row['content_id'])?>"><i class="fa fa-pencil m-5"></i></a>
          </li>
          <li>
            <? $stmt = $this->pdo->prepare("SELECT member_id, firstname, lastname, member_hash, profile_img FROM members WHERE member_id=:member_id LIMIT 1");
            $stmt->execute(array(':member_id' => $row['member_id']));
            if($stmt->rowCount() == 1) { $usr_data = $stmt->fetch(PDO::FETCH_ASSOC); ?>
              <? if(empty($usr_data['profile_img'])) { ?>
                <img data-name="<?=$usr_data['firstname']." ".$usr_data['lastname']?>" class="w-25 b-r-5 profile_img" title="Created by: <?=$usr_data['firstname']." ".$usr_data['lastname']?>">
              <? } else { ?>
                <img class="w-25 b-r-5" src="<?=URL_PATH.$usr_data['profile_img']?>" title="Created by: <?=$usr_data['firstname']." ".$usr_data['lastname']?>">
              <? } ?>
            <? } ?>
          </li>
          <li><i class="fa fa-angle-double-right f-s-14 p-5 f-c-lightgray"></i></li>
          <li class="p-r-5">
            <? $stmt = $this->pdo->prepare("SELECT member_id, firstname, lastname, member_hash, profile_img FROM members WHERE member_id=:member_id LIMIT 1");
            $stmt->execute(array(':member_id' => $row['to_member_id']));
            if($stmt->rowCount() == 1) { $usr_data = $stmt->fetch(PDO::FETCH_ASSOC); ?>
              <? if(empty($usr_data['profile_img'])) { ?>
                <img data-name="<?=$usr_data['firstname']." ".$usr_data['lastname']?>" class="w-25 b-r-5 profile_img" title="Assigned To: <?=$usr_data['firstname']." ".$usr_data['lastname']?>">
              <? } else { ?>
                <img class="w-25 b-r-5" src="<?=URL_PATH.$usr_data['profile_img']?>" title="Assigned To: <?=$usr_data['firstname']." ".$usr_data['lastname']?>">
              <? } ?>
            <? } ?>
          </li>

        </ul>
      </div>
    </div>
		<?
    return ob_get_clean();
	}


}
?>
