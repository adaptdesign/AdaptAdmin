<?php

class d_members_feed extends global_members {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
	//=========================================
  public function p_feed_query() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // GET QUERY
    $return = $this->set_query("",""); $sql = $return[0]; $arg = $return[1];
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    $return = ""; //$return = disarray($sql,$arg);
    $stmt = $this->pdo->prepare($sql); $results = array();
    if($stmt->execute($arg)) {
      while($row = $this->_format_row_dbtype($stmt->fetch(PDO::FETCH_ASSOC))){
        $row['r_id'] = $row['member_id'];
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
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page); return; } //==============
		?>
    <div class="b-s-0 b-dashed b-s-l-1 b-c-default relative">
      <ul class="ul-unstyled ul-inline clearfix relative uppercase f-w-500">
        <li><div class="bg-white p-5"><div class="bg-l-primary p-8 p-t-6 p-b-4 f-s-12"><?=$this->widget_data['title']?></div></div></li>
        <li class="right p-l-15 select2-b-p-0" style="min-width:150px">
          <?
    			$f_perpage["targets"] = array("feedlooper_members");
    			$this->_show_filters_perpage($f_perpage); ?>
        </li>
        <li class="fill">
          <?
    			$f_main["targets"] = array("feedlooper_members");
    			$this->_show_filters_main($f_main); ?>
        </li>
      </ul>

			<div class="row m-0 m-t-15">
				<div class="col-md-12 p-15 p-t-0 p-r-0">
					<div class="row feedlooper" id="feedlooper_members" page="1"  link="<?=URL_PATH?>?page=<?=encode("d_members_feed")?>&action=<?=encode('p_feed_query')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">

					</div>
					<div id="feedlooper_members_msgbox" class="white"></div>
				</div>
			</div>
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
    <div r_id="<?=e_a($row,'r_id')?>" class="col-sm-6 col-md-4 col-lg-3 col-xl-2 m-b-15">
      <div class="fx_square f-c-h-themed bg-white fx_transition2 fx_box_shadow2 fx_box_shadow2_h text-center elementlink" title="View <?=$row['firstname']." ".$row['lastname']?>'s profile" href="<?=URL_PATH?>members/<?=$row['member_id']?>">
        <div class="">
          <div class="float-top p-10 bg-c-t-white-8">
            <h3><? if($this->_check_online($row['last_active'])) { echo "<i class='fa fa-circle f-c-success'></i>"; } ?> <?=$row['firstname']." ".$row['lastname']?></h3>
          </div>
          <div class="float-bottom p-5 bg-c-t-white-8 uppercase"><? if(!empty($row['member_hash'])) { echo "#".$row['member_hash']; } ?></div>
          <? if (empty($row['profile_img'])) { ?>
            <img class="w-100p profile_img" data-name="<?=$row['firstname']." ".$row['lastname']?>">
          <? } else { ?>
            <img class="w-100p" src="<?=URL_PATH.$row['profile_img']?>">
          <? } ?>
        </div>
      </div>
    </div>
		<?
    return ob_get_clean();
	}


}
?>
