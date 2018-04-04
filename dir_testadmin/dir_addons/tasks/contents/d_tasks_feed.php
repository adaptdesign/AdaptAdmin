<?php

class d_tasks_feed extends global_tasks {

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
        $row['r_id'] = $row['content_id'];
        $row['html'] = $this->show_feed_item($row);
        $results[] = $row;
      }
    }
    echo json_encode(array('status'=>'success','message'=>$return,'data'=>json_encode($results),'js'=>$this->feed_js()));
	}
	//=========================================
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
          <a class="do_ajax btn btn-themed" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_tasks_addedit")?>&action=<?=encode("p_addedit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>"> ADD <?=$this->widget_data['title']?></a>
        </li>
        <li class="right p-l-15 select2-b-p-0" style="min-width:150px">
          <?
    			$f_perpage["targets"] = array("feedlooper_assigned","feedlooper_progress","feedlooper_complete");
    			$this->_show_filters_perpage($f_perpage); ?>
        </li>
        <li class="fill">
          <?
    			$f_main["targets"] = array("feedlooper_assigned","feedlooper_progress","feedlooper_complete");
    			$this->_show_filters_main($f_main); ?>
        </li>
      </ul>
      <div class="row m-0 m-t-15">
        <div class="col-md-12 p-0">
          <div class="row equal m-0">
            <div class="col-md-4 p-0 p-r-25">
              <div class="bg-white f-c-primary b-r-1 b-s-0 b-c-primary b-solid b-s-l-2 p-10 f-s-12 f-w-600 uppercase arrow-r-s">
                Assigned
              </div>
              <input type="hidden" class="feedfilter" target="feedlooper_assigned" value="level|1">
              <div class="feedlooper con_sortable p-t-10" id="feedlooper_assigned" page="1" r_max="5"  link="<?=URL_PATH?>?page=<?=encode("d_tasks_feed")?>&action=<?=encode('p_feed_query')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&level=<?=encode('1')?>" updateurl="<?=URL_PATH?>?page=<?=encode("a_tasks")?>&action=<?=encode('p_edit_task')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">

              </div>
              <div id="feedlooper_assigned_msgbox" class="white"></div>
            </div>
            <div class="col-md-4 p-0 p-r-25 b-s-0 b-dashed b-c-default b-s-l-1">
              <div class="bg-white f-c-warning b-r-1 b-s-0 b-c-warning b-solid b-s-l-2 p-10 f-s-12 f-w-600 uppercase arrow-r-s">
                In Progress
              </div>
              <input type="hidden" class="feedfilter" target="feedlooper_progress" value="level|2">
              <div class="feedlooper con_sortable p-t-10" id="feedlooper_progress" page="1" r_max="5"  link="<?=URL_PATH?>?page=<?=encode("d_tasks_feed")?>&action=<?=encode('p_feed_query')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&level=<?=encode('2')?>" updateurl="<?=URL_PATH?>?page=<?=encode("a_tasks")?>&action=<?=encode('p_edit_task')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">

              </div>
              <div id="feedlooper_progress_msgbox" class="white"></div>
            </div>
            <div class="col-md-4 p-0 b-s-0 b-dashed b-c-default b-s-l-1">
              <div class="bg-white f-c-success b-r-1 b-s-0 b-c-success b-solid b-s-l-2 p-10 f-s-12 f-w-600 uppercase">
                Complete
              </div>
              <input type="hidden" class="feedfilter" target="feedlooper_complete" value="level|3">
              <div class="feedlooper con_sortable p-t-10" id="feedlooper_complete" page="1" r_max="5"  link="<?=URL_PATH?>?page=<?=encode("d_tasks_feed")?>&action=<?=encode('p_feed_query')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&level=<?=encode('3')?>" updateurl="<?=URL_PATH?>?page=<?=encode("a_tasks")?>&action=<?=encode('p_edit_task')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">

              </div>
              <div id="feedlooper_complete_msgbox" class="white"></div>
            </div>
          </div>
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
			$("#feedlooper_assigned, #feedlooper_progress, #feedlooper_complete").sortable({
	      connectWith: ".con_sortable",
				scroll: false,
				items: ".sortabletask",
		    placeholder: "sortable_placeholder",
		    handle: '.handle',
				zIndex: 500,
				forcePlaceholderSize: true,
				receive: function(event, ui) {
					var dropped = ui.item;
					var droppedOn = $(this);
					var t_level = "";
					if(droppedOn.attr('id') == "feedlooper_assigned") { t_level = "1"; }
					else if(droppedOn.attr('id') == "feedlooper_progress") { t_level = "2"; }
					else if(droppedOn.attr('id') == "feedlooper_complete") { t_level = "3"; }

					if(dropped.hasClass('b-c-l-success')) {
						if(confirm('Are you sure you want to remove this from completion status?')) {

						} else {
     					$(ui.sender).sortable('cancel');
							return;
						}
					}
					$.ajax({
						url: droppedOn.attr("updateurl"),
						data: {'item_id':$(dropped).attr("id"),'t_level':t_level},
						type: 'post',
						error: function(XMLHttpRequest, textStatus, errorThrown){
							alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
							$(this).sortable('cancel');
     					$(ui.sender).sortable('cancel');
						},
						success: function(data){
							result = data;
							result = unescape(result);
							result = result.split("|");
							outcome = result[0];
							task_time = result[1];
							note = result[2];
							if(outcome=='true') {
								if(droppedOn.attr('id') == "feedlooper_assigned") {
									$(dropped).removeClass("b-c-l-primary b-c-l-warning b-c-l-success");
									$(dropped).addClass("b-c-l-primary");
									$(dropped).find(".fa").removeClass("f-c-primary f-c-warning f-c-success");
									$(dropped).find(".fa").addClass("f-c-primary");
									$(dropped).find(".handle .m-t-10").html(task_time);
								}	else if(droppedOn.attr('id') == "feedlooper_progress") {
									$(dropped).removeClass("b-c-l-primary b-c-l-warning b-c-l-success");
									$(dropped).addClass("b-c-l-warning");
									$(dropped).find(".fa").removeClass("f-c-primary f-c-warning f-c-success");
									$(dropped).find(".fa").addClass("f-c-warning");
									$(dropped).find(".handle .m-t-10").html(task_time);
								}	else if(droppedOn.attr('id') == "feedlooper_complete") {
									$(dropped).removeClass("b-c-l-primary b-c-l-warning b-c-l-success");
									$(dropped).addClass("b-c-l-success");
									$(dropped).find(".fa").removeClass("f-c-primary f-c-warning f-c-success");
									$(dropped).find(".fa").addClass("f-c-success");
									$(dropped).find(".handle .m-t-10").html(task_time);
								}
								$("#"+droppedOn.attr('id')+"_msgbox").html("");
								new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+note }).show();
							} else {
								new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+outcome }).show();
								$(this).sortable('cancel');
       					$(ui.sender).sortable('cancel');
							}
						}
					});
				},
	    });
			$(".profile_img").initial();
			$('[title]').tooltip({ container:'body', placement:'bottom' });
		</script>
		<?
    return ob_get_clean();
	}
  //===========================================================================================
	public function show_feed_item($row) { ob_start();
    if($row['approval'] == "1") { $draggable = "no"; } else { $draggable = "yes"; }

    $assigned_to[] = $row['member_id'];
    if(!empty($row['linked_to'])) {
      $lt_sections = explode("|", $row['linked_to']);
      foreach($lt_sections as $lt_section) {
        if(strpos($lt_section, "-".$this->plugins['groupmembers']['plugin_id'].",") !== false) {
          $lt_section = explode(",", $lt_section);
          $assigned_to[] = strtr($lt_section[1], array('-' => ''));
        }
      }
    }
    if ($row['level'] == '0') { $s_class = "f-c-default"; $s_name = "b-c-l-default"; }
    elseif ($row['level'] == '1') { $s_class = "f-c-primary"; $s_name = "b-c-l-primary"; }
    elseif ($row['level'] == '2') { $s_class = "f-c-warning"; $s_name = "b-c-l-warning"; }
    elseif ($row['level'] == '3') { $s_class = "f-c-success"; $s_name = "b-c-l-success"; }
    else { $s_class = "f-c-default"; $s_name = "b-c-l-default"; }

    $sortabletask = "";
    if($draggable == "yes") {
      if (in_array($this->member_data['member_id'], $assigned_to) || $this->member_data['group_level'] > '2') {
        $sortabletask = "sortabletask";
      }
    }
    ?>
    <div class="bg-white relative b-r-2 m-b-10 fx_box_shadow1_h sortabletask <?=$s_name?> <? if($row['approval'] == "1") { echo "danger"; } ?>" id="<?=encode($row['content_id'])?>" status="<?=$row['level']?>">
      <ul class="ul-unstyled ul-inline absolute w-auto top-0 right-0 f-s-12">
        <? if($row['priority'] == '2') { ?>
          <li class="p-l-5"><i class="fa fa-fire f-c-danger" title="High Priority"></i></li>
        <? } ?>
        <? if($row['privacy'] == "2") { ?>
          <li class="p-l-5"><i class="fa fa-user-secret f-c-gray" title="Private: Visible only to you and any members linked to it"></i></li>
        <? } ?>
        <? if(!empty($row['customstatus'])) { ?>
          <li class="p-l-5"><div class="f-s-11 f-w-400 uppercase f-c-white p-l-5 p-r-5 b-r-l-5" style="background:<?=$row['customstatuscolor']?>"><?=$row['customstatus']?></div></li>
        <? } ?>
      </ul>
      <div class="p-15 handle">
        <div class="f-s-13 f-w-600"><i class="fa fa-<?=$this->widget_data['font_icon']?> <?=$s_class?>"></i> <?=$row['title']?> </div>
        <div class="m-t-10 f-s-11 f-w-500 uppercase">
          <? if($row['level'] != '3') { ?>
            Due: <span title="<?=date("F j, Y, g:i a", strtotime($row['due_date']))?>"><?=$this->_time_diff($row['due_date'])?></span>
          <? } else { ?>
            Completed: <span class="text-success" title="<?=date("g j, Y, g:i a", strtotime($row['complete_date']))?>"><?=date("M-j-Y", strtotime($row['complete_date']))?></span>
          <? } ?>
        </div>
      </div>
      <div class="b-s-0 b-s-t-1 b-dashed b-c-default bg-white p-5">

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
          <? foreach($assigned_to as $member) { ?>
            <li class="p-r-5">
              <? $stmt = $this->pdo->prepare("SELECT member_id, firstname, lastname, member_hash, profile_img FROM members WHERE member_id=:member_id LIMIT 1");
              $stmt->execute(array(':member_id' => $member));
              if($stmt->rowCount() == 1) { $usr_data = $stmt->fetch(PDO::FETCH_ASSOC); ?>
                <? if(empty($usr_data['profile_img'])) { ?>
                  <img data-name="<?=$usr_data['firstname']." ".$usr_data['lastname']?>" class="w-25 b-r-5 profile_img" title="Assigned To: <?=$usr_data['firstname']." ".$usr_data['lastname']?>">
                <? } else { ?>
                  <img class="w-25 b-r-5" src="<?=URL_PATH.$usr_data['profile_img']?>" title="Assigned To: <?=$usr_data['firstname']." ".$usr_data['lastname']?>">
                <? } ?>
              <? } ?>
            </li>
          <? } ?>

        </ul>
      </div>
    </div>
		<?
    return ob_get_clean();
	}

}
?>
