<?php

class d_layout_feed extends global_layout {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
	//=========================================
	public function p_show_feed() {
    $this->_set_menu_widgets();
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page); return; } //==============
    ?>
    <div class="b-s-0 b-dashed b-s-l-1 b-c-default relative">
      <ul class="nav nav-tabs bg-white">
        <li class="active"><a data-toggle="tab" href="#layout_edit">Edit Layout</a></li>
        <li class=""><a data-toggle="tab" href="#layout_templates">Layout Templates</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane animated fadeIn fade active in" id="layout_edit">

          <div class="row equal m-0 m-t-15">
            <div class="col-md-3 p-l-0">

              <ul class="nav nav-tabs nav-stacked nav-unselect nav-multi nav-hash bg-white" hash_id="5ac00ce82bf71">
                <li id="page_<?=$this->menu_widgets['dashboard']['layout_id']?>">
                  <a data-toggle="tab" class="bg-white nav-load <? if($this->menu_widgets['dashboard']['status'] == "1") { echo "bg-l-danger"; } ?>" href="#<?=uniqid()?>" target="#edit_layout_tabs" link="<?=URL_PATH?>?page=<?=encode("d_layout_editwidget")?>&action=<?=encode('p_edit_widget')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($this->menu_widgets['dashboard']['layout_id'])?>"><i class="fa fa-<?=$this->menu_widgets['dashboard']['font_icon']?> m-r-5"></i> <?=$this->menu_widgets['dashboard']['title']?></a>
                </li>
                <li id="page_<?=$this->menu_widgets['groupmembers']['layout_id']?>">
                  <a data-toggle="tab" class="bg-white nav-load <? if($this->menu_widgets['dashboard']['status'] == "1") { echo "bg-l-danger"; } ?>" href="#<?=uniqid()?>" target="#edit_layout_tabs" link="<?=URL_PATH?>?page=<?=encode("d_layout_editwidget")?>&action=<?=encode('p_edit_widget')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($this->menu_widgets['groupmembers']['layout_id'])?>"><i class="fa fa-<?=$this->menu_widgets['groupmembers']['font_icon']?> m-r-5"></i> <?=$this->menu_widgets['groupmembers']['title']?></a>
                </li>

              </ul>
              <ul id="0" class="nav nav-tabs nav-stacked nav-unselect nav-multi nav-hash bg-white sortable_plugin h-n-50" hash_id="5ac00ce82d4ff" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_layout_order")?>">
                <? if(!empty($this->menu_items)) { foreach($this->menu_items as $row) { ?>
                  <li class="sortableplug" id="page_<?=$row['layout_id']?>">
                    <i class="fa fa-bars pull-right hand" title="Drag to sort"></i>
                    <a data-toggle="tab" class="bg-white nav-load <? if($row['status'] == "1") { echo "bg-l-danger"; } ?>" href="#<?=uniqid()?>" target="#edit_layout_tabs" link="<?=URL_PATH?>?page=<?=encode("d_layout_editwidget")?>&action=<?=encode('p_edit_widget')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($row['layout_id'])?>" title="Click to edit"><i class="fa fa-<?=$row['font_icon']?> m-r-5"></i> <?=$row['title']?></a>
                  </li>
                <? } } ?>
              </ul>
              <ul class="nav nav-tabs nav-stacked nav-unselect nav-multi nav-hash bg-white" hash_id="5ac00ce82dc89">
                <li id="page_<?=$this->menu_widgets['groupsettings']['layout_id']?>">
                  <a data-toggle="tab" class="bg-white nav-load <? if($row['status'] == "1") { echo "bg-l-danger"; } ?>" href="#<?=uniqid()?>" target="#edit_layout_tabs" link="<?=URL_PATH?>?page=<?=encode("d_layout_editwidget")?>&action=<?=encode('p_edit_widget')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($this->menu_widgets['groupsettings']['layout_id'])?>"><i class="fa fa-<?=$this->menu_widgets['groupsettings']['font_icon']?> m-r-5"></i> <?=$this->menu_widgets['groupsettings']['title']?></a>
                </li>
              </ul>
              <div class="make_sortable" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_layout_order")?>">

                <? if(!empty($this->menu_folders)) { foreach($this->menu_folders as $menu_folder) { ?>
                  <div class="sortable bg-gray" id="page_<?=$menu_folder['settings']['layout_id']?>">
                    <i class="fa fa-bars pull-right hand p-15" title="Drag to sort"></i>
                    <a class="do_ajax block p-15 f-w-500 uppercase f-c-default" da_type="load" da_target="main_modal_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditfolder")?>&action=<?=encode("p_edit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($menu_folder['settings']['layout_id'])?>"><i class="fa fa-folder f-s-17 m-r-5 m-t-n4 align-middle"></i> <?=$menu_folder['settings']['title']?></a>
                    <ul id="<?=$menu_folder['settings']['layout_id']?>" class="nav nav-tabs nav-stacked nav-unselect nav-multi nav-hash bg-white sortable_plugin h-n-50" hash_id="5ac00ce82e3b9" link="<?=URL_PATH?>?page=<?=encode("a_layout")?>&action=<?=encode("p_layout_order")?>">
                      <? if(!empty($menu_folder['plugins'])) { foreach($menu_folder['plugins'] as $row) { ?>
                        <li class="sortableplug" id="page_<?=$row['layout_id']?>">
                          <i class="fa fa-bars pull-right hand" title="Drag to sort"></i>
                          <a data-toggle="tab" class="bg-white nav-load <? if($row['status'] == "1") { echo "bg-l-danger"; } ?>" href="#<?=uniqid()?>" target="#edit_layout_tabs" link="<?=URL_PATH?>?page=<?=encode("d_layout_editwidget")?>&action=<?=encode('p_edit_widget')?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($row['layout_id'])?>" title="Click to edit"><i class="fa fa-<?=$row['font_icon']?> m-r-5"></i> <?=$row['title']?></a>
                        </li>
                      <? } } ?>
                    </ul>
                  </div>
                <? } } ?>
              </div>
            </div>
            <div class="col-md-9 p-0">
              <div class="absolute top-0 right-0 left-0 bg-white p-15">
                <a class="do_ajax btn btn-themed pull-right m-l-xs" da_type="load" da_target="main_modal_content" data-postdata="none" da_link="<?=URL_PATH?>?page=<?=encode("d_layout_addeditfolder")?>&action=<?=encode("p_addedit")?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>">Add Folder</a>
                <h2 class="p-t-10"><i class="fa fa-object-ungroup f-c-themed"></i> Edit Layout</h2>
                <div class="clearfix m-b-15"></div>
                <div>
                  Use the main widgets menu to the left to configure the groups layout. Drag menu items and folders to arrange them. Use the Add Folder button to create new menu folders. Click on menu items to edit them. From the widget edit window you can attach new sub widgets, add additional content options as well as adjusting the main menu widgets settings.
                </div>
              </div>

              <div class="tab-content relative bg-gray" id="edit_layout_tabs">

              </div>
            </div>
          </div>

        </div>
        <div class="tab-pane animated fadeIn fade" id="layout_templates">
?

        </div>
        <div class="tab-pane animated fadeIn fade" id="t3">
          test3
        </div>
      </div>




    </div>
    <script>
    $(".sortable_plugin").sortable({
      connectWith: ".sortable_plugin",
      scroll: false,
      items: ".sortableplug",
      placeholder: "sortable_placeholder",
      handle: '.hand',
      zIndex: 500,
      forcePlaceholderSize: true,
      receive: function(event, ui) {
        $.ajax({
          type: "POST",
          url: $(this).attr('link'),
          data: { type: "changeParent", item_id: ui.item[0].id, parent_id: this.id },
          success: function (msg) {
            result = msg;
            result = unescape(result);
            result = result.split("|");
            outcome = result[0];
            note = result[1];
            if(outcome=='true') {

            } else {
              new Noty({ text: msg }).show();
            }
          }
        });
      },
      update: function(event, ui) {
        $.ajax({
          type: "POST",
          url: $(this).attr('link'),
          data: { type: "orderPages", pages: $(this).sortable('serialize') },
          success: function (msg) {
            result = msg;
            result = unescape(result);
            result = result.split("|");
            outcome = result[0];
            note = result[1];
            if(outcome=='true') {

            } else {
              new Noty({ text: msg }).show();
            }
          }
        });
      }
    });
    $(document).on("change", "#option_type", function(e) {
      if($(this).val() == "select"){
        $('#option_content').show();
      } else {
        $('#option_content').hide();
      }
    });
    $(document).on("click", "#add_option", function(e) {
      $(".option_section").append('<div class="input-group"><input type="text" class="form-control" name="option_options[]"><span class="input-group-btn"><button class="btn btn-default remove_option" type="button"><i class="fa fa-trash" title="Remove"></i></button></span></div>');
    });
    $(document).on("click", ".remove_option", function(e) {
      $(this).closest('.input-group').remove();
    });

    var hue = Math.floor(Math.random() * 360);
    var pastel = 'hsl(' + hue + ', 100%, 87.5%)';
    $('.text').css('background-color', pastel);
    </script>
		<?
	}
  //=========================================

}
?>
