<?php

class _global_admin extends _global {
  public $widget_data = array();
  public $link = null;
  public $link_plugin_id = null;
  public $link_content_id = null;
  public $content_data_id = null;
  public $content_data = array();
  public $page_title = null;
  public $fail_page = array();

  public $menu_widgets = array();
  public $menu_folders = array();
  public $non_menu_items = array();
  public $menu_items = array();
  public $page_widgets = array();

  //=========================================
  public function __construct(){
		parent::__construct();

    // SET GET ITEM ID
    if(!empty($_GET['item'])) {
      $item = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', decode($_GET['item']));
    } elseif(!empty($this->ses_params)) {
      $params = explode("/", $this->ses_params);
      if(!empty($params[0])) { $item = $params[0]; }
    }
    if(!empty($item) && is_numeric($item)) { $this->content_data_id = $item; }


    if(!empty($_GET['widg'])) { $this->_set_widget_data(preg_replace('/[^a-zA-Z0-9-_]/','', decode($_GET['widg']))); }
    if(!empty($_GET['link'])) { $this->_set_link(preg_replace('/[^a-zA-Z0-9-_\,\|]/','', decode($_GET['link']))); }
  }
  //=========================================
  public function _set_widget_data($widget_data) {
    // CHECK FOR ERRORS
    if(!is_numeric($widget_data)){ return; }
    // SET WIDGET DATA
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_id=:layout_id AND group_id=:group_id LIMIT 1");
		$stmt->execute(array(':layout_id' => $widget_data, ':group_id' => $this->member_data['group_id']));
		if($stmt->rowCount() == 1) {
			$this->widget_data = $this->_format_widg($stmt->fetch(PDO::FETCH_ASSOC));
		}
  }
  //=========================================
  public function _set_link($link) {
    $this->link = $link;
    $pts = explode(",", strtr($this->link, array('-' => '')));
    if(is_numeric($pts[0])){ $this->link_plugin_id = $pts[0]; }
    if(is_numeric($pts[1])){ $this->link_content_id = $pts[1]; }
  }
  //=========================================
  public function _check_online($last_active) {
		$time_check = new DateTime;
		$time_check = date_modify($time_check, '-5 minutes');
		$last_active = new DateTime($last_active);
		if($last_active > $time_check) {
			return true;
		} else {
			return false;
		}
	}
  //=========================================
  public function _time_diff($datetime, $short = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
    $string = array('y'=>'year','m'=>'month','w'=>'week','d'=>'day','h'=>'hour','i'=>'minute','s'=>'second');
		foreach($string as $k => &$v) {
			if($diff->$k) { $v = $diff->$k . ($short ? '' : ' ') . ($short ? substr($v, 0, 1) : $v) . ($diff->$k > 1 ? ($short ? '' : 's') : ''); } else { unset($string[$k]); } // ? 's' : ''
		}
    $string = array_slice($string, 0, 1);
    $result = $string ? implode(', ', $string) . ($short ? '' : ($now > $ago ? ' ago' : '')) : ($short ? '' : 'just now');
    return $result;
	}
  //=========================================
  public function _format_widg($row) {
    $row = $this->_format_row_global($row);
    if(!empty($this->plugins)) { foreach($this->plugins as $plugin) {
      if($plugin['plugin_id'] == $row['plugin_id']) { $temp_data = $plugin; break; }
    } }
    if(empty($temp_data)) { return $row; }
    if(empty($row['has_menuwidget'])) { $row['has_menuwidget'] = $temp_data['has_menuwidget']; }
    if(empty($row['title'])) { $row['title'] = $temp_data['title']; }
    if(empty($row['pl_title'])) { $row['pl_title'] = $temp_data['title']; }
    if(empty($row['font_icon'])) { $row['font_icon'] = $temp_data['font_icon']; }
    return $row;
  }
  //=========================================
  public function _show_filters_main($f_options) {
    if(!empty($f_options)) { foreach($f_options as $name=>$row) {
      if($name == "targets") { if(!empty($row)) { $f_targets = implode("|",$row); } }
      if($name == "filters") { if(!empty($row)) { $f_filters = $row; } }
    } }
    if(empty($f_filters)) { $f_filters = array("search","created_by","assigned_to","between_dates"); }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "<div>Session has expired. Please <a href='".URL_PATH."'>log in</a></div>"; } // CHECK LOGGED IN
    if(empty($this->widget_data)) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK WIDGET_DATA
    if(empty($this->link)) { $this->fail_page[] = "<div>Error: Unable to find link</div>"; } // CHECK FILTER TARGET
    if(empty($f_targets)) { $this->fail_page[] = "<div>Error: Unable to find filter target</div>"; } // CHECK LINK
    if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "<div>Access to this content is restricted</div>"; } // CHECK ACCESS LEVEL
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    ?>
    <div class="selectgroup dropdown">
      <div class="select2-b-p-0 permaplaceholder" data-placeholder="+ FILTERS...">
        <select class="form-control select2 select2parent feedfilter" target="<?=$f_targets?>" multiple="multiple"></select>
      </div>
      <div class="dropdown-menu w-100p show_dropdown">
        <? if(in_array("search", $f_filters)) { ?>
          <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
            <label class="col-md-4 p-t-10 f-s-13 uppercase">Search:</label>
            <div class="col-md-8">
              <div class="input-group">
                <input id="<?$c=uniqid()?><?=$c?>" type="text" class="form-control" />
                <a class="input-group-addon addsearch"><i class="fa fa-search"></i></a>
              </div>
            </div>
          </div>
        <? } ?>
        <? if(in_array("created_by", $f_filters)) { ?>
          <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
            <label class="col-md-4 p-t-10 f-s-13 uppercase">Created By:</label>
            <div class="col-md-8">
              <select id="<?$c=uniqid()?><?=$c?>" class="form-control select2 select2child" multiple="multiple">
                <?
                $sql = "
                SELECT U.member_id, U.firstname, U.lastname, U.profile_img, U.member_hash, U.last_active
                FROM connections M
                LEFT JOIN members U ON (M.member_id = U.member_id)
                WHERE M.connection_type='groupmember' AND M.group_id ='".$this->member_data['group_id']."' AND M.status='2' AND U.status='2' AND U.approval IN ('2') AND M.approval IN ('2')
                ORDER BY U.member_level DESC, U.firstname ASC, U.lastname ASC
                ";
                $stmt = $this->pdo->prepare($sql); $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                  <option value="creator|<?=$row['member_id']?>"><?=$row['firstname']." ".$row['lastname']?></option>
                <? } ?>
              </select>
            </div>
          </div>
        <? } ?>
        <? if(in_array("assigned_to", $f_filters)) { ?>
          <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
            <label class="col-md-4 p-t-10 f-s-13 uppercase">Assigned To:</label>
            <div class="col-md-8">
              <select id="<?$c=uniqid()?><?=$c?>" class="form-control select2 select2child" multiple="multiple">
                <?
                $sql = "
                SELECT U.member_id, U.firstname, U.lastname, U.profile_img, U.member_hash, U.last_active
                FROM connections M
                LEFT JOIN members U ON (M.member_id = U.member_id)
                WHERE M.connection_type='groupmember' AND M.group_id ='".$this->member_data['group_id']."' AND M.status='2' AND U.status='2' AND U.approval IN ('2') AND M.approval IN ('2')
                ORDER BY U.member_level DESC, U.firstname ASC, U.lastname ASC
                ";
                $stmt = $this->pdo->prepare($sql); $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                  <option value="assigned|<?=$row['member_id']?>"><?=$row['firstname']." ".$row['lastname']?></option>
                <? } ?>
              </select>
            </div>
          </div>
        <? } ?>
        <? if(in_array("between_dates", $f_filters)) { ?>
          <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
            <label class="col-md-4 p-t-10 f-s-13 uppercase">Between Dates:</label>
            <div class="col-md-8">
              <input id="<?$c=uniqid()?><?=$c?>" type="text" class="form-control datepicker <?$d=uniqid()?><?=$d?>">
            </div>
          </div>
        <? } ?>
      </div>
    </div>
    <script>
      $(".<?=$d?>").dateRangePicker({
        separator : ' ~ ',
        format: 'YYYY.MM.DD HH:mm:ss',
        autoClose: false,
        container: $(".<?=$d?>").closest("div"),
        time: {
          enabled: true
        }
      });
      $('.select2').select2().trigger("select2:select");
    </script>
    <?
  }
  //=========================================
  public function _show_filters_perpage($f_options) {
    if(!empty($f_options)) { foreach($f_options as $name=>$row) {
      if($name == "targets") { if(!empty($row)) { $f_targets = implode("|",$row); } }
      if($name == "perpages") { if(!empty($row)) { $f_perpages = $row; } }
    } }
    if(empty($f_perpages)) { $f_perpages = array("3","20","50","100","200"); }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "<div>Session has expired. Please <a href='".URL_PATH."'>log in</a></div>"; } // CHECK LOGGED IN
    if(empty($this->widget_data)) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK WIDGET_DATA
    if(empty($this->link)) { $this->fail_page[] = "<div>Error: Unable to find link</div>"; } // CHECK FILTER TARGET
    if(empty($f_targets)) { $this->fail_page[] = "<div>Error: Unable to find filter target</div>"; } // CHECK LINK
    if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "<div>Access to this content is restricted</div>"; } // CHECK ACCESS LEVEL
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    ?>
    <select id="<?$c=uniqid()?><?=$c?>" class="form-control  b-s-0 f-s-11 select2 feedfilter" target="<?=$f_targets?>">
      <? if(!empty($f_perpages)) { foreach($f_perpages as $perpage) { if(!empty($perpage)){ ?>
        <option value="perpage|<?=$perpage?>"><?=$perpage?> PER PAGE</option>
      <? } } } ?>
    </select>
    <?
  }
  //=========================================
  public function _set_menu_widgets() {
    $stmt = $this->pdo->prepare("SELECT *	FROM layout WHERE layout_type='menu_folder' AND status='2' AND level<=? AND group_id=? ORDER BY order_id ASC");
    $stmt->execute(array($this->member_data['group_level'] , $this->member_data['group_id']));
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $this->menu_folders[$row['layout_id']]["settings"] = $row;
    }
    $stmt = $this->pdo->prepare("SELECT *	FROM layout WHERE layout_type='widg_menu' AND status='2' AND level<=? AND group_id=? ORDER BY order_id ASC, title ASC");
    $stmt->execute(array($this->member_data['group_level'] , $this->member_data['group_id']));
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $row = $this->_format_widg($row);
      if(!empty($row['pl_title'])) { $this->menu_widgets[$row['pl_title']] = $row; }
    }
    foreach($this->menu_widgets as $key=>$row) {
      if($row['has_menuwidget'] == "1") {

      } elseif(!empty($this->menu_folders[$row['parent_id']])) {
        if($this->menu_folders[$row['parent_id']] > 0) {
          $this->menu_folders[$row['parent_id']]["plugins"][$key] = $row;
        }
      } else {
        $this->menu_items[$key] = $row;
      }
    }
  }
  //=========================================
  public function _set_page_widgets() {
    // CHECK FOR ERRORS
		if(!loggedIn()){ $this->fail_page[] = "<div>Session has expired. Please <a href='".URL_PATH."'>log in</a></div>"; } // CHECK LOGGED IN
		if(empty($this->widget_data)) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK WIDGET_DATA
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "<div>Access to this content is restricted</div>"; } // CHECK ACCESS LEVEL
		// DISPLAY ERRORS
		if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }

    $sql = "SELECT * FROM layout WHERE status = '2' "; // SQL START
		if(!empty($this->content_data_id)) {
      $sql .= "AND (layout_type='widg_cont_dash' OR layout_type='widg_cont_page') ";
    } else {
      $sql .= "AND (layout_type='widg_page_dash' OR layout_type='widg_page_page') ";
    }
    $sql .= "AND level<=? "; $arg[] = $this->member_data['group_level'];
    $sql .= "AND group_id=? "; $arg[] = $this->member_data['group_id'];
    $sql .= "AND parent_id=? "; $arg[] = $this->widget_data['plugin_id'];
    $sql .= "ORDER BY order_id ASC, created_date ASC"; // SQL END
		$stmt = $this->pdo->prepare($sql);
    $stmt->execute($arg);
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$row = $this->_format_widg($row);
			if($row['layout_type'] == "widg_cont_dash" || $row['layout_type'] == "widg_page_dash") { $this->page_widgets['dash'][] = $row; }
			elseif($row['layout_type'] == "widg_cont_page" || $row['layout_type'] == "widg_page_page") { $this->page_widgets['page'][] = $row; }
		}
  }
  //=========================================
  public function _set_linkable_to() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ return; }
    if(empty($this->widget_data)) { return; }
    if(empty($this->link)) { return; }
    if($this->member_data['group_level'] < $this->widget_data['level']) { return; }
    // SET INIT LINKED_TO
    if(empty($this->content_data['linked_to'])) { $this->content_data['linked_to'] = $this->link; }
    $links = explode("|", $this->content_data['linked_to']);
    // SET LINKABLE PLUGINS
    if(!empty($this->widget_data['opt_linkables'])) { $widgets = explode(",", $this->widget_data['opt_linkables']); }
		?>
		<div class="selectgroup dropdown">
			<div class="permaplaceholder" data-placeholder="+ LINK TO...">
				<select class="form-control select2 select2parent" <? if(empty($widgets)) { echo "disabled"; } ?> multiple="multiple" name="<?=encode("linked_to")?>[]"></select>
			</div>
			<div class="dropdown-menu w-100p show_dropdown">
				<?
				if(!empty($widgets)) { foreach($widgets as $widget) { if(!empty($widget)) {
					if($widget == "groupmembers") {
						?>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-4 p-t-10 f-s-13 uppercase"><?=ucfirst($this->plugins[$widget]['title'])?>:</label>
							<div class="col-md-8">
								<select id="<?$c=uniqid()?><?=$c?>" class="form-control select2 select2child" multiple="multiple" name="<?=encode("linked_to")?>[]">
									<?
									$filters = "SELECT G.groupmember_id, G.approval, G.level, M.member_id, M.firstname, M.lastname, M.member_hash, M.profile_img, M.email, M.last_active, G.created_date
									FROM groupmembers G
									LEFT JOIN members M ON (G.member_id = M.member_id)
									WHERE G.group_id ='".$this->member_data['group_id']."' AND M.member_id NOT IN ('".$this->member_data['member_id']."') ";
									if($this->member_data['group_level'] > "2"){
										$filters .= "AND M.member_level IN ('2','3','4','5') AND G.level IN ('1','2','3','4','5') ";
									} else {
										$filters .= "AND G.approval='2' AND M.member_level IN ('2','3','4','5') AND G.level IN ('2','3','4','5') ";
									}
									foreach($this->pdo->query($filters." ORDER BY G.approval ASC, G.level DESC, M.firstname ASC, M.lastname ASC, G.created_date DESC") as $row) { ?>
										<option value="<?=encode("-".$this->plugins['groupmembers']['plugin_id'].",".$row['member_id']."-")?>" <? if(in_array("-".$this->plugins['groupmembers']['plugin_id'].",".$row['member_id']."-", $links)) { echo "selected"; $added[] = "-".$this->plugins['groupmembers']['plugin_id'].",".$row['member_id']."-"; } ?>><?=$row['firstname']." ".$row['lastname']?></option>
									<? } ?>
								</select>
							</div>
						</div>
					<? } elseif($widget != "groupmembers") {
						?>
						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
							<label class="col-md-4 p-t-10 f-s-13 uppercase"><?=ucfirst($this->plugins[$widget]['title'])?>:</label>
							<div class="col-md-8">
								<select id="<?$c=uniqid()?><?=$c?>" class="form-control select2 select2child" multiple="multiple" name="<?=encode("linked_to")?>[]">
									<? foreach($this->pdo->query("SELECT * FROM content WHERE plugin_id='".$this->plugins[$widget]['plugin_id']."' AND (lower(linked_to) LIKE '%-reply,%') IS NOT TRUE AND group_id='".$this->member_data['group_id']."' AND level > '1' ORDER BY title ASC") as $row) { ?>
										<option value="<?=encode("-".$this->plugins[$widget]['plugin_id'].",".$row['content_id']."-")?>" <? if(in_array("-".$this->plugins[$widget]['plugin_id'].",".$row['content_id']."-", $links)) { echo"selected"; $added[] = "-".$this->plugins[$widget]['plugin_id'].",".$row['content_id']."-"; } ?>><?=$row['title']?></option>
									<? } ?>
								</select>
							</div>
						</div>
					<? } ?>
					<?
				} } }
				foreach($links as $link) {
					if(!empty($added) && in_array($link, $added)) { }
					else { ?><input type="hidden" name="<?=encode("linked_to")?>[]" value="<?=encode($link)?>"><? }
				}
				?>
			</div>
		</div>
		<?
	}
  //=========================================
  public function _set_additional_options() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "<div>Session has expired. Please <a href='".URL_PATH."'>log in</a></div>"; } // CHECK LOGGED IN
    if(empty($this->widget_data)) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK WIDGET_DATA
    if(empty($this->link)) { $this->fail_page[] = "<div>Error: Unable to find link</div>"; } // CHECK LINK
    if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "<div>Access to this content is restricted</div>"; } // CHECK ACCESS LEVEL
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    // SET WIDGET PLUGIN DATA
    if(!empty($this->plugins)) { foreach($this->plugins as $plugin) { if($plugin['plugin_id'] == $this->widget_data['plugin_id']) { $plugin_data = $plugin; break; } } }

		if($plugin_data['has_addoptions'] == "2"){
			$stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_type='addoption' AND status IN ('2') AND plugin_id=:plugin_id AND group_id=:group_id ORDER BY created_date ASC");
      $stmt->execute(array(':plugin_id' => $plugin_data['plugin_id'], ':group_id' => $this->member_data['group_id']));
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$parts = explode("-||-",$row['other']); foreach($parts as $value) { $other = explode("-|-",$value); if(empty($row[$other[0]])) { $row[$other[0]] = $other[1]; } }
				if(empty($row['order_id'])) { $row['order_id'] = 'data-gs-auto-position="true" data-gs-width="4"'; }
				$grouppluginoptions[] = $row;
			}
			if(!empty($grouppluginoptions)) { $cbid = 1; ?>
				<div style="padding: 0 15px 20px;">
					<div class="grid-stack grid-stack-form-static">
						<? foreach($grouppluginoptions as $row) { $cbid++; ?>
							<div class="grid-stack-item" <?=$row['order_id']?> data-gs-height="1">
								<div class="grid-stack-item-content">
									<? if($row['option_type'] == "input") { ?>
										<label><?=$row['title']?></label>
										<input type="text" class="form-control" name="<?=encode("inputs")?>[<?=encode($row['title'])?>]" value="<? if(!empty($this->content_data[$row['title']])){ echo $this->content_data[$row['title']]; } ?>">
									<? } elseif($row['option_type'] == "checkbox") { ?>
										<div class="form-group m-t-20 m-b-10">
											<div class="input-ggroup">
												<div class="checkbox checkbox-success checkbox-inline">
													<input type="hidden" name="<?=encode("inputse")?>[<?=encode($row['title'])?>]" value="<?=encode("1")?>">
													<input id="ts<?=$cbid?>" name="<?=encode("inputse")?>[<?=encode($row['title'])?>]" value="<?=encode("2")?>" type="checkbox" <?  if(!empty($this->content_data[$row['title']])){ if($this->content_data[$row['title']] == "2") { echo "checked"; } } ?>>
													<label for="ts<?=$cbid?>" style="font-weight: bold;"><?=$row['title']?>?</label>
												</div>
											</div>
										</div>
									<? } elseif($row['option_type'] == "select") { ?>
										<label><?=$row['title']?></label>
										<select class="form-control select2" name="<?=encode("inputse")?>[<?=encode($row['title'])?>]">
											<? if(!empty($row['option_options'])) { $option_parts = explode("|", $row['option_options']); } ?>
											<? foreach($option_parts as $option) { ?>
												<option value="<?=encode($option)?>" <?  if(!empty($this->content_data[$row['title']])){ if($this->content_data[$row['title']] == $option) { echo "selected"; } } ?>><?=$option?></option>
											<? } ?>
										</select>
									<? } elseif($row['option_type'] == "label") { ?>
										<div class="grid-option-label"><i class="fa fa-angle-double-right text-themed"></i> <?=$row['title']?></div>
									<? } ?>
								</div>
							</div>
						<? } ?>
					</div>
					<div class="clearfix"></div>
				</div>
				<script>
					var waitForFinalEvent=function(){var b={};return function(c,d,a){a||(a="I am a banana!");b[a]&&clearTimeout(b[a]);b[a]=setTimeout(c,d)}}();
					var fullDateString = new Date();
					$('.grid-stack-form-static').gridstack({
						disableDrag: true,
						disableResize: true,
						cellHeight: '30px',
						verticalMargin: '5px'
					});
					waitForFinalEvent(function() {
						$('.grid-stack-form-static .grid-stack-item').each(function(){
							var grid = $(this).closest('.grid-stack').data('gridstack');
							var gsi = $(this).find(".grid-stack-item-content");
							var newHeight = Math.ceil((gsi[0].scrollHeight + grid.opts.verticalMargin) / (grid.cellHeight() + grid.opts.verticalMargin));
							grid.resize($(this),null,newHeight);
						});
					}, 300, fullDateString.getTime());
					autosize(document.querySelectorAll('textarea'));
					$('[title]').tooltip({ container:'body', placement:'bottom' });
				</script>
			<?
      }
    }
	}
  //=========================================


}
?>
