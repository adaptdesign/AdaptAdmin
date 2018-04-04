<?php

class d_layout_editwidget extends global_layout {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_edit_widget() {
    if(!empty($this->content_data_id)) { $this->set_content_data("",$this->content_data_id); }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if(empty($this->content_data)) { $this->fail_page[] = "Unable to load content data"; }
		if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page); return; } //==============
    // SET WIDGET WIDGETS
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE parent_id=:parent_id AND group_id=:group_id AND (layout_type='widg_cont_dash' OR layout_type='widg_cont_page' OR layout_type='widg_page_dash' OR layout_type='widg_page_page') ORDER BY order_id ASC, created_date ASC");
    $stmt->execute(array(':parent_id' => e_a($this->content_data,'plugin_id'), ':group_id' => $this->member_data['group_id']));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $row = $this->_format_widg($row);
      $all_widg[] = $row;
      if($row['layout_type'] == "widg_cont_dash") { $cont_dash_widg[] = $row; }
      elseif($row['layout_type'] == "widg_cont_page") { $cont_page_widg[] = $row; }
      elseif($row['layout_type'] == "widg_page_dash") { $page_dash_widg[] = $row; }
      elseif($row['layout_type'] == "widg_page_page") { $page_page_widg[] = $row; }
    }
    ?>
    <div class="animated fadeIn">
      <ul class="nav nav-tabs nav-hash bg-white" hash_id="5ac00ceac2af3<?=e_a($this->content_data,'layout_id')?>">
        <li class="active"><a data-toggle="tab" href="#le_globalo<?=e_a($this->content_data,'layout_id')?>">Global Options</a></li>
        <li class=""><a data-toggle="tab" href="#le_pageo<?=e_a($this->content_data,'layout_id')?>">Page Options</a></li>
        <li class=""><a data-toggle="tab" href="#le_pagew<?=e_a($this->content_data,'layout_id')?>">Page Widgets</a></li>
        <li class=""><a data-toggle="tab" href="#le_itemw<?=e_a($this->content_data,'layout_id')?>">Item Widgets</a></li>
      </ul>
      <div class="tab-content bg-white p-15">
        <div class="tab-pane animated fadeIn fade active in" id="le_globalo<?=e_a($this->content_data,'layout_id')?>">
          <a class="do_ajax btn btn-themed pull-right m-l-xs" da_type="load" da_target="main_modal_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditoption")?>&action=<?=encode("p_addedit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&parent=<?=encode(e_a($this->content_data,'plugin_id'))?>">Add Input</a>
          <h2 class="p-t-10"><i class="fa fa-plus f-c-themed"></i> Additional Inputs</h2>
          <div class="clearfix m-b-15"></div>
          <div class="clearfix h-n-37 bg-lightgray b-s-1 b-dashed b-c-default">

            <div class="clearfix h-100p m-10 m-l-0 m-r-0 grid-stack grid-stack-form-drag" link="<?=URL_PATH?>?page=<?=encode("plugins")?>&action=<?=encode("a_addoptionorder")?>">
              <?
              $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_type='add_option' AND plugin_id=:plugin_id AND group_id=:group_id ORDER BY created_date ASC");
              $stmt->execute(array(':plugin_id' => e_a($this->content_data,'plugin_id'), ':group_id' => $this->member_data['group_id']));
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if(empty($row['order_id'])) { $row['order_id'] = 'data-gs-auto-position="true" data-gs-width="4"'; }
                ?>
                <div class="grid-stack-item" <?=$row['order_id']?> data-id="<?=encode($row['layout_id'])?>" data-gs-height="1" data-gs-min-height="1" data-gs-max-height="1">
                  <div class="grid-stack-item-content">
                    <a class="do_ajax" da_type="load" da_target="main_modal_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditoption")?>&action=<?=encode("p_edit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&parent=<?=encode(e_a($this->content_data,'plugin_id'))?>&item=<?=encode($row['layout_id'])?>" title="Option Type:  <?=e_a($row,'option_type')?>"><?=$row['title']?></a>
                  </div>
                </div>
              <? } ?>
            </div>

          </div>

        </div>
        <div class="tab-pane animated fadeIn fade" id="le_pageo<?=e_a($this->content_data,'layout_id')?>">
          <form class="form_redirect" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_edit_widget")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">
    				<div class="row m-b-xs">
    					<div class="col-md-12">
    						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-3 p-t-10 f-s-13 uppercase" title="Select whether posted content will be visible for the entire group or only to the member who posted it and those linked to it">Posting Privacy: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
    							<div class="col-md-9">
                    <select class="form-control select2" name="<?=encode("inputse")?>[<?=encode("privacy")?>]">
    									<option value="<?=encode("1")?>" <? if(e_a($this->content_data,'privacy') == "1") { echo "selected"; } ?>>Entire Group</option>
    									<option value="<?=encode("2")?>" <? if(e_a($this->content_data,'privacy') == "2") { echo "selected"; } ?>>Linked Members</option>
    								</select>
                  </div>
    						</div>
    						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-3 p-t-10 f-s-13 uppercase" title="Select whether you would like content posted anywhere in the group or only content posted to this widget/page to be displayed">Widget Content: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
    							<div class="col-md-9">
                    <select class="form-control select2" name="<?=encode("inputse")?>[<?=encode("widgetonly")?>]">
    									<option value="<?=encode("1")?>" <? if(e_a($this->content_data,'widgetonly') == "1") { echo "selected"; } ?>>Posted Anywhere</option>
    									<option value="<?=encode("2")?>" <? if(e_a($this->content_data,'widgetonly') == "2") { echo "selected"; } ?>>Posted To Widget</option>
    								</select>
                  </div>
    						</div>
    						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-3 p-t-10 f-s-13 uppercase">Alternate Title:</label>
    							<div class="col-md-9"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("title")?>]" value="<?=e_a($this->content_data,'title')?>"></div>
    						</div>
    						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-3 p-t-10 f-s-13 uppercase">Alternate Icon: <a href="http://fontawesome.io/icons/" target="_target" class="text-muted small">[Icon List]</a></label>
    							<div class="col-md-9"><input class="form-control" type="text" name="<?=encode("inputs")?>[<?=encode("font_icon")?>]" value="<?=e_a($this->content_data,'font_icon')?>"></div>
    						</div>
    						<div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-3 p-t-10 f-s-13 uppercase">Access Level:</label>
    							<div class="col-md-9">
                    <select class="form-control select2" name="<?=encode("inputse")?>[<?=encode("level")?>]">
    									<option value="<?=encode('2')?>" <? if(e_a($this->content_data,'level') == "2") { echo "selected"; } ?>>Everyone</option>
    									<option value="<?=encode('3')?>" <? if(e_a($this->content_data,'level') == "3") { echo "selected"; } ?>>Admins</option>
    									<option value="<?=encode('4')?>" <? if(e_a($this->content_data,'level') == "4") { echo "selected"; } ?>>Super Admins</option>
    								</select>
                  </div>
    						</div>
                <div class="clearfix bg-lightgray p-10 b-s-0 b-dashed b-c-default b-s-b-1">
    							<label class="col-md-3 p-t-10 f-s-13 uppercase" title="Select which plugins new content will be linkable to">Linkable To: <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
    							<div class="col-md-9">
                    <select class="form-control select2" multiple="multiple" name="opt_linkables[]">
      								<? if(!empty(e_a($this->content_data,'opt_linkables'))) { $opt_linkables_array = explode(",", e_a($this->content_data,'opt_linkables')); } ?>
      								<? foreach($this->plugins as $plugin) { if($plugin['status'] > "1" && $plugin['has_pagewidget'] == "2") { ?>
      									<option value="<?=$plugin['title']?>" <? if(!empty($plugin['title']) && !empty($opt_linkables_array)) { if(in_array($plugin['title'], $opt_linkables_array)) { echo "selected"; } } ?>><?=$plugin['title']?></option>
      								<? } } ?>
      							</select>
                  </div>
    						</div>

                <div class="clearfix bg-lightgray p-10 p-t-20 p-b-20 b-s-0 b-dashed b-c-default b-s-b-1">
                  <label class="col-md-3" title="Select this option if you would like to require new content to be approved by and admin before posting">Require Approval? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
                  <div class="col-md-9">
                    <div class="input-group">
                      <div class="checkbox checkbox-success checkbox-inline">
                        <input type="hidden" name="<?=encode("inputse")?>[<?=encode("approval")?>]" value="<?=encode("1")?>">
                        <input name="<?=encode("inputse")?>[<?=encode("approval")?>]" value="<?=encode("2")?>" type="checkbox" <? if(e_a($this->content_data,'approval') == '2') { echo "checked"; }?>>
                        <label></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix bg-lightgray p-10 p-t-20 p-b-20 b-s-0 b-dashed b-c-default b-s-b-1">
                  <label class="col-md-3" title="Check this option if you wish for group members to receive notifications when new content is added to this widget">Notifications? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
                  <div class="col-md-9">
                    <div class="input-group">
                      <div class="checkbox checkbox-success checkbox-inline">
                        <input type="hidden" name="<?=encode("inputse")?>[<?=encode("notifications")?>]" value="<?=encode("1")?>">
                        <input name="<?=encode("inputse")?>[<?=encode("notifications")?>]" value="<?=encode("2")?>" type="checkbox" <? if(e_a($this->content_data,'notifications') == '2') { echo "checked"; }?>>
                        <label></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix bg-lightgray p-10 p-t-20 p-b-20 b-s-0 b-dashed b-c-default b-s-b-1">
                  <label class="col-md-3" title="Select this option to disable access to this widget.">Disable Widget? <span class="f-w-500 f-c-gray f-s-11">[?]</span></label>
                  <div class="col-md-9">
                    <div class="input-group">
                      <div class="checkbox checkbox-success checkbox-inline">
                        <input type="hidden" name="<?=encode("inputse")?>[<?=encode("status")?>]" value="<?=encode("2")?>">
                        <input name="<?=encode("inputse")?>[<?=encode("status")?>]" value="<?=encode("1")?>" type="checkbox" <? if(e_a($this->content_data,'status') == '1') { echo "checked"; }?>>
                        <label></label>
                      </div>
                    </div>
                  </div>
                </div>
    					</div>
    				</div>
            <div class="p-t-20 clearfix">
      				<input type="hidden" name="<?=encode("wheres")?>[<?=encode("layout_id")?>]" value="<?=encode($this->content_data['layout_id'])?>">
              <? if($this->member_data['group_level'] > '3' || $this->content_data['member_id'] == $this->member_data['member_id']) { ?>
    						<a class="cnfm_me btn btn-danger" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_deletewidget")?>" istype="" isform="" message="Are you sure you want to delete this widget?" data-item_id="<?=encode($this->content_data['layout_id'])?>">Delete</a>
    					<? } ?>
    					<button type="submit" class="btn btn-themed pull-right">Update</button>
            </div>
      		</form>

        </div>
        <div class="tab-pane animated fadeIn fade" id="le_pagew<?=$this->content_data['layout_id']?>">
          <a class="do_ajax btn btn-themed pull-right m-l-xs" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditwidget")?>&action=<?=encode("p_addedit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&parent=<?=encode($this->content_data['plugin_id'])?>&type=<?=encode("widg_page_page")?>"><i class="fa fa-cube"></i> Add Widget</a>
          <h2 class="p-t-10"><i class="fa fa-cube f-c-themed"></i> Page Widgets</h2>
          <div class="clearfix m-b-15"></div>

          <div class="">
            <ul class="scrollbars make_sortable p-10 bg-lightgray b-s-1 b-dashed b-c-default h-37 tabs-pages" style="display:block;" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_layoutorder")?>">
              <? if(!empty($page_page_widg)) { foreach($page_page_widg as $row) { ?>
                <li class="sortable bg-lightgray" id="page_<?=$row['layout_id']?>" item_id="<?=$row['layout_id']?>">
                  <a class="do_ajax" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditwidget")?>&action=<?=encode("p_edit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&parent=<?=encode($this->content_data['plugin_id'])?>&item=<?=encode($row['layout_id'])?>">
                    <i class="fa fa-<?=$row['font_icon']?>"></i> <?=$row['title']?>
                    <i class="fa fa-bars p-5 m-t-n5 m-r-n10 m-b-n5 hand ui-sortable-handle" title="Drag to sort"></i>
                  </a>
                </li>
              <? } } ?>
            </ul>
          </div>
        </div>
        <div class="tab-pane animated fadeIn fade" id="le_itemw<?=$this->content_data['layout_id']?>">
          <a class="do_ajax btn btn-themed pull-right m-l-xs" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditwidget")?>&action=<?=encode("p_addedit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&parent=<?=encode($this->content_data['plugin_id'])?>&type=<?=encode("widg_cont_page")?>"><i class="fa fa-cube"></i> Add Widget</a>
          <h2 class="p-t-10"><i class="fa fa-cube f-c-themed"></i> Content Widgets</h2>
          <div class="clearfix m-b-15"></div>

          <div class="">
            <ul class="scrollbars make_sortable p-10 bg-lightgray b-s-1 b-dashed b-c-default h-37 tabs-pages" style="display:block;" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_layoutorder")?>">
              <? if(!empty($cont_page_widg)) { foreach($cont_page_widg as $row) { ?>
                <li class="sortable bg-lightgray" id="page_<?=$row['layout_id']?>" item_id="<?=$row['layout_id']?>">
                  <a class="do_ajax" da_type="load" da_target="doc_editor_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditwidget")?>&action=<?=encode("p_edit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&parent=<?=encode($this->content_data['plugin_id'])?>&item=<?=encode($row['layout_id'])?>">
                    <i class="fa fa-<?=$row['font_icon']?>"></i> <?=$row['title']?>
                    <i class="fa fa-bars p-5 m-t-n5 m-r-n10 m-b-n5 hand ui-sortable-handle" title="Drag to sort"></i>
                  </a>
                </li>
              <? } } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <script>
    $('.grid-stack-form-drag').gridstack({
      cellHeight: '30px'
    });
    $('.grid-stack-drag').gridstack({
      float: false
    });
    if(typeof resizeGrid == 'function') { resizeGrid(); }
    </script>
    <?
  }


}
?>
