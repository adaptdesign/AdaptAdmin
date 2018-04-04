<?php

class d_activities_feed extends global_activities {

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
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
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
        $row['r_id'] = $row['activity_id'];
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
        <? if(!empty($this->parent_plugin_data)) { ?>
          <li><div class="bg-white p-5"><div class="bg-l-primary p-8 p-t-6 p-b-4 f-s-12"><?=$this->parent_plugin_data['title']?></div></div></li>
        <? } ?>
        <li><div class="bg-white p-5"><div class="bg-l-primary p-8 p-t-6 p-b-4 f-s-12"><?=$this->widget_data['title']?></div></div></li>
        <li class="right p-l-15">
          <a class="do_ajax btn btn-themed" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_activities_addedit")?>&action=<?=encode("p_addedit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>"> ADD <?=$this->widget_data['title']?></a>
        </li>
        <li class="right p-l-15 select2-b-p-0" style="min-width:150px">
          <?
    			$f_perpage["targets"] = array("feedlooper_activities");
    			$this->_show_filters_perpage($f_perpage); ?>
        </li>
        <li class="fill">
          <?
    			$f_main["targets"] = array("feedlooper_activities");
    			$this->_show_filters_main($f_main); ?>
        </li>
      </ul>

			<div class="row equal m-0 m-t-15">
				<div class="col-md-12 p-15 p-t-0 p-r-0">
					<div class="row bg-white fx_section_table p-5 m-b-5 f-w-500 f-s-10">
						<div class="col-md-6 h-auto fx_section_table_img f-w-500 f-s-10">
							<div class="w-56">

							</div>
							<div>
								NAME/EMAIL:
							</div>
						</div>
						<div class="col-md-3 h-auto">
							PHONE
						</div>
						<div class="col-md-3 h-auto">
							LAST ACTION:
						</div>
					</div>
					<div class="feedlooper" id="feedlooper_activities" page="1"  link="<?=URL_PATH?>?page=<?=encode("d_activities_feed")?>&action=<?=encode('p_feed_query')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">

					</div>
					<div id="feedlooper_activities_msgbox" class="white"></div>
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
		<div r_id="<?=e_a($row,'r_id')?>" class="row bg-white fx_section_table p-5 b-r-1 m-b-1 b-s-0 b-solid b-s-l-2 b-c-white f-s-12 bg-l-h-primary fx_transition1 elementlink">
			<?=disarray($row)?>
		</div>
		<?
    return ob_get_clean();
	}


}
?>
